<p>DNUI (<b>D</b>elete <b>N</b>ot <b>U</b>sed <b>I</b>mage) will search images from the database and try to find it on
    every Post and Page if one image have one reference in this one,
    if any reference is found, the plugin will tell you that the image is not used.
</p>
<h3>Why i have to do Backup?</h3>
<p>
    This plugin will delete images and information's in your server and the database, so you have to do one BACKUP every time you want
    to use this plugin.
</p>
<h3>Is the backup system from the DNUI plugin enough?</h3>
<p>
   Yes and no, if you have the backup option active, the plugin will try to do one backup of every image you are try to delete, but this is not the main purpose of the DNUI plugin, so is not bull proof <br/>
   In the WordPress.org plugin page you can find a lots of Backup Plugin, so they will have better code for make Backups
</p>
<h3>Is really the image not used / unused?</h3>
<p>
    Yes and not, the 'not used' label, tell you that the imageName.imageType (toto.jpg) is not found in any Post or Page
    <br/>
    So if you have another plugin, for example 'E-commerce X' that use the toto.jpg in one HTML code or SHORTCODE, the
    DNUI plugin can't find any reference, so you will have one false 'not used' label
</p>

<h3>How to fix the false 'not used' label?</h3>
<p>
    This question can be hard to answer <br>
    I build this plugin for help you to fix this problem, you have some options:
<ul>
    <li>
        Use the Ignore Size Option, you can select one or more options (use Ctrl+Click) to ignore the size's
    </li>
    <li>
        You can dev your own CheckerImage[Plugin].php code, and add this to CheckersDNUI (you can send me the code and i will put this in the wordpress.org version)
    </li>
    <li>
        Ask me to do it this plugin compatible with the X Plugin (Only for Pro version)
    </li>
</ul>
</p>
<h3>Where i can found the version pro?</h3>
<p>
    I'm working on it
</p>

