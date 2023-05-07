<?php

/**
 * phimcap - PHpIMageCAPtcha
 * @see https://github.com/gueff/phimcap
 */
class Phimcap
{
    /**
     * @param string $sCaptchaText
     * @param string $sAbsPathToFont
     * @return bool
     */
    public static function image (string $sCaptchaText = '', string $sAbsPathToFont = '/usr/share/fonts/truetype/freefont/FreeMono.ttf')
    {
        if (true === empty($sCaptchaText))
        {
            exit();
        }

        $iLength = strlen($sCaptchaText);
        $iWidth = ($iLength * 30);
        $iHeight = 50;
        $oGdImage = imagecreatetruecolor($iWidth, $iHeight);
        imagealphablending($oGdImage, true);
        imagesavealpha($oGdImage, true);
        $iBgColor = imagecolorallocatealpha($oGdImage, 255, 255, 255, 127);
        imagefill($oGdImage, 0, 0, $iBgColor);

        $iSize = 15;
        $iColor = imagecolorallocate($oGdImage, 0, 0, 0);

        for ($i = 0; $i < strlen($sCaptchaText); $i++)
        {
            $char = $sCaptchaText[$i];
            imagettftext($oGdImage, $iSize, 0, 10 + $i * 30, 35, $iColor, $sAbsPathToFont, $char);
        }

        header("Content-Type: image/png");
        imagepng($oGdImage);
        imagedestroy($oGdImage);

        exit();
    }

    /**
     * @param int $iLentgh
     * @return string
     */
    public static function text(int $iLentgh = 5)
    {
        $iLentgh = abs($iLentgh);
        ($iLentgh < 5 || $iLentgh > 10) ? $iLentgh = 5 : false;

        $sChar = "abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ1234567890";
        $sText = "";

        for ($i = 0; $i < $iLentgh; $i++)
        {
            $char = $sChar[rand(0, strlen($sChar) - 1)];
            $sText.= $char;
        }

        return $sText;
    }
}