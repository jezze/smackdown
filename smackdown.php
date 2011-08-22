<?php

class Smackdown
{

    private static $tags;

    public function __construct()
    {

    }

    private static function find_tag($id)
    {

        foreach (Smackdown::$tags as $tag)
        {

            if ($tag[1] == $id)
                return $tag;

        }

        return null;

    }

    private static function get_tag($matches)
    {

        $name = $matches[1];
        $id = $matches[2];

        if (!$tag = Smackdown::find_tag($id))
            return $name;

        switch ($tag[2])
        {

            case 'url':

                return '<a href="' . $tag[3]. '">' . $name . '</a>';

            default:

                return $name;

        }

    }

    private static function apply_rules($input)
    {

        $p = array(
            "/\[[^\]]*\]/",
            "/#### (.+) ####/",
            "/### (.+) ###/",
            "/## (.+) ##/",
            "/# (.+) #/",
            "/\*(.+)\*/",
            "/(\r?\n){2,}/",
            "/^/",
            "/$/",
            "/\r?\n/"
        );

        $r = array(
            "",
            "<h4>$1</h4>",
            "<h3>$1</h3>",
            "<h2>$1</h2>",
            "<h1>$1</h1>",
            "<b>$1</b>",
            "</p><p>",
            "<p>",
            "</p>",
            "<br/>"
        );

        return preg_replace($p, $r, $input);

    }

    private static function apply_tags($input)
    {

        Smackdown::$tags = array();

        preg_match_all("/\[([0-9A-z]+):([0-9A-z]+):([^\]]+)\]/", $input, Smackdown::$tags, PREG_SET_ORDER);

        return preg_replace_callback("/\^([^:]+):([0-9A-z]+)\^/", "Smackdown::get_tag", $input);

    }

    public static function parse($input)
    {

        $input = Smackdown::apply_tags($input);
        $input = Smackdown::apply_rules($input);

        return $input;

    }

}

?>
