<?php

/**
 * Class Css
 *
 * Resolves CSS custom properties from :root definitions.
 */
class Css {

    /**
     * Replaces all var(--name) with values defined in any :root block.
     *
     * @param string $str
     * @return string
     */
    public static function replaceVars(string $str): string {
        $vars = [];
        $rootBlockPattern = '/:root\s*{(.*?)}/s';
        $varDefPattern = '/--([\w-]+)\s*:\s*([^;]+);/';
        $varUsePattern = '/var\(--([\w-]+)\)/';
        preg_match_all($rootBlockPattern, $str, $blocks, PREG_SET_ORDER);
        foreach ($blocks as $match) {
            $block = $match[1];
            preg_match_all($varDefPattern, $block, $definitions, PREG_SET_ORDER);
            foreach ($definitions as $def) {
            $vars[$def[1]] = trim($def[2]);}
            $str = str_replace($match[0], '', $str);
        }
        $resolver = function ($m) use ($vars) {
        return $vars[$m[1]] ?? ''; };
        $str = preg_replace_callback(
        $varUsePattern,$resolver,$str);
        return $str;
    }
}
