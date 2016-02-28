<?php

/**
 *
 * @author nicearma
 */
class ConvertWordpressToDNUI
{


    public static function convertIdsToImagesDNUI($imagesIds)
    {

        $images = array();

        if (!empty($imagesIds)) {

            $base = wp_upload_dir();

            foreach ($imagesIds as $key => $imageId) {

                $imageDNUI = ConvertWordpressToDNUI::convertIdToImageDNUI($imageId["id"]);
                if (!empty($imageDNUI)) {
                    array_push($images, $imageDNUI);
                }

            }


        }
        return $images;

    }

    public static function convertIdToImageDNUI($imageId)
    {
        $imageDNUI = array();
        $attachment = wp_get_attachment_metadata($imageId);
        if (!empty($attachment) && array_key_exists('file', $attachment)) { //TODO: something is not right, see way, this was found in my production server


            $baseDirs = explode("/", $attachment["file"]);
            $name = array_pop($baseDirs);
            $uploadDir = implode("/", $baseDirs);
            $imageDNUI = new ImageDNUI($imageId, $name, 'original', $attachment['file'], $attachment['width'] . 'x' . $attachment['height']);

            if (array_key_exists('sizes', $attachment)) {
                foreach ($attachment['sizes'] as $nameSize => $size) {
                    $imageSizeDNUI = new ImageSizeDNUI($size['file'], $nameSize, $size['width'] . 'x' . $size['height'], $uploadDir . '/' . $size['file']);
                    $imageDNUI->addImageSize($imageSizeDNUI);
                }
            };
        }

        return $imageDNUI;

    }

    //based in https://codex.wordpress.org/Gallery_Shortcode
    public static function convertIdToGalleriesSizes($postIds)
    {
        //TODO:maybe put this in object
        $info = array();
        if (empty($postIds)) {
            return $info;
        }
        foreach ($postIds as $postId) {
            $galleries = get_post_galleries($postId['id'], false);
            foreach ($galleries as $gallery) {

                $idsImage = array();

                if (!array_key_exists('ids', $gallery)) {
                    //based in the Developers - Things to consider of https://codex.wordpress.org/Gallery_Shortcode
                    $imageAttachements = get_children(array(
                        'post_parent' => $postId['id'],
                        'post_status' => 'inherit',
                        'post_type' => 'attachment',
                        'post_mime_type' => 'image'
                    ));

                    if (empty($imageAttachements)) {
                        continue;
                    } else {
                        foreach ($imageAttachements as $attachement) {
                            $idsImage[] = $attachement->ID;
                        }
                    }

                } else {

                    $idsImage = explode(',', $gallery['ids']);

                }

                foreach ($idsImage as $id) {

                    if (!array_key_exists($id, $info)) {
                        $info[$id] = array('sizes' => array());
                    }


                    if (array_key_exists('size', $gallery)) {
                        if ($gallery['size'] == 'full') {
                            $size = "original";
                        } else {
                            $size = $gallery['size'];
                        }

                    } else {
                        $size = 'thumbnail';
                    }

                    if (!in_array($size, $info[$id]['sizes'])) {
                        $info[$id]['sizes'][] = $size;
                    }
                }
            }
        }

        return $info;
    }

    public static function convertIdToHTMLShortCodes($postIds, $row = 'content')
    {
        //https://codex.wordpress.org/Function_Reference/get_shortcode_regex

        $pattern = get_shortcode_regex();
        $htlmShortCode = array();
        $special = array("\n", "\t");
        foreach ($postIds as $postId) {
            $post = get_post($postId['id']);

            if ($row == 'content') {
                $text = $post->post_content;

            } else if ($row == 'excerpt') {
                $text = $post->post_excerpt;
            } else {
                $text = '';
            }
            if (preg_match_all('/' . $pattern . '/s', $text, $matches)
                && array_key_exists(2, $matches)
            ) {
                unset($post);

                $htlm = '';

                foreach ($matches[0] as $shortCode) {

                    $htlm .= do_shortcode($shortCode);
                }

                if (!empty($htlm)) {
                    $htlm = str_replace($special, "", $htlm);
                    $htlmShortCode[] = $htlm;
                }


            }


        }
        return $htlmShortCode;

    }

}
