<?php
/**
 * @var \App\View\AppView $this
 * @var string $linkText
 * @var string $url
 * @var string $bgColor
 * @var string $textColor
 */
?>
<div class="big-action-button"><!--[if mso]>
    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="<?= $url ?>" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="10%" stroke="f" fillcolor="<?= $bgColor ?>">
        <w:anchorlock/>
        <center>
    <![endif]-->
        <a href="<?= $url ?>" style="background-color:<?= $bgColor ?>;border-radius:4px;color:<?= $textColor ?>;display:inline-block;line-height:40px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;"><?= $linkText ?></a>
    <!--[if mso]>
        </center>
    </v:roundrect>
    <![endif]-->
</div>
