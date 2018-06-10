<?php
/**
 * @var \App\View\AppView $this
 * @var string $instanceName
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width" />
    <meta name="format-detection" content="telephone=no" />
    <style type="text/css">
        /* <![CDATA[ */
        body {
            margin: 0;
            padding: 0;
            mso-line-height-rule: exactly;
            min-width: 100%;
            background-color: #efefef;
        }

        .wrapper {
            padding: 20px 0;
            background-color: #efefef;
            display: table;
            table-layout: fixed;
            width: 100%;
            min-width: 620px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        .center {
            text-align: center;
        }

        .text-pad {
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        a {
            color: #c21429;
        }

        h1, h2 {
            margin: 0;
            padding: 0;
            color: #fff;
        }

        h1 {
            font-weight: bold;
            font-size: 22px;
            line-height: 33px;
        }

        h2 {
            font-weight: normal;
            font-size: 20px;
            line-height: 30px;
        }

        .big-action-button {
            font-weight: bold;
            padding-top: 21px;
        }

        table.centered {
            width: 602px;
            margin-right: auto;
            margin-left: auto;
        }

        table.header {
            background-color: #c21429;
        }

        .header-spacer {
            font-size: 0;
            line-height: 0;
            height: 30px;
        }

        table.content {
            background-color: #fff;
            font-size: 15px;
        }

        table.footer {
            font-size: 12px;
            color: #999999;
        }

        table.footer a {
            color: #999999;
        }

        table.footer a:hover {
            color: #999999;
        }


        @media screen and (min-width: 0) {
            .wrapper {
                text-rendering: optimizeLegibility;
            }
        }

        @media only screen and (max-width: 620px) {
            .wrapper {
                min-width: 320px;
            }

            table.centered {
                width: 302px;
            }
        }
        /* ]]> */
    </style>
</head>
<body>
<center class="wrapper">
    <table class="header centered">
        <tbody>
        <tr>
            <td class="header-spacer">&nbsp;</td>
        </tr>
        <tr>
            <td>
                <h1 class="center"><?= $this->get('instanceName') ?></h1>
                <h2 class="center"><?= $this->get('title') ?></h2>
            </td>
        </tr>
        <tr>
            <td class="header-spacer">&nbsp;</td>
        </tr>
        </tbody>
    </table>
    <table class="content centered">
        <tbody><tr>
            <td class="text-pad">
                <?php echo $this->fetch('content') ?>
                <br><br>
                <?= __('Kind regards') ?>,<br>
                <?= $instanceName ?><br>
                <?= $this->Email->linkToHomepage() ?>
            </td>
        </tr></tbody>
    </table>
    <table class="footer centered">
        <tbody><tr>
            <td class="text-pad">
                <p class="center"><?= $this->Email->linkToImprint() ?></p>
            </td>
        </tr></tbody>
    </table>
</center>
</body>
</html>
