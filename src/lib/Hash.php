<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/11/2016
 * Time: 16:12
 */
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

    /*public static function set($tableau, $chemin, $valeur)
    {
        $cheminHash = explode('.',$chemin);
        $nb = count($cheminHash);
        $i=1;

        foreach($cheminHash as $boutChemin ){

            if(isset($tableau[$boutChemin])){
                $tableau = $tableau[$boutChemin];
            }else if($nb == $i){
                $tableau[$boutChemin] = $valeur;
            }else{
                $tableau[$boutChemin] = [];
            }

        }
        return $tableau;
    }*/

    public static function set($tableau, $chemin, $valeur)
    {
        echo "CHEMIN A FAIRE : $chemin\n";
        $cheminHash = explode('.', $chemin);
        $newPath=self::createPath($cheminHash, $valeur);
        return $tableau + $newPath;
    }

    protected static function createPath(Array $path, $value, $i=0)
    {
        $out=[];
//        foreach($path as $p){
        if(count($path)===0){
            return $value;
        }else{
            $chunk=array_shift($path);
            var_dump("Passe $i : chunck: $chunk ".count($path)." elements restants");
            $out[$chunk]=self::createPath($out, $value, $i+1);
            }
//        }
        echo "  > Returning :\n";
        var_dump($out);
        return $out;
    }


}