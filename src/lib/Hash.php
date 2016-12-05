<?php

abstract class Hash
{

    public static function get($tableau, $chemin)
    {
        $cheminHash = explode('.', $chemin);
        foreach ($cheminHash as $boutChemin) {
            if (isset($tableau[$boutChemin])) {
                $tableau = $tableau[$boutChemin];
            } else {
                return null;
            }
        }
        return $tableau;
    }

    public static function set($tableau, $chemin, $valeur, $overwrite = true)
    {
        $cheminHash = explode('.', $chemin);
        $newPath = self::createPath($cheminHash, $valeur);
        if ($overwrite) {
            return array_merge($tableau, $newPath);
        } else {
            return array_merge_recursive($tableau, $newPath);
        }

    }

    protected static function createPath(Array $path, $value)
    {
        $out = [];
        if (count($path) === 0) {
            return $value;
        } else {
            $chunk = array_shift($path);
            $out[$chunk] = self::createPath($path, $value);
        }
        return $out;
    }
}