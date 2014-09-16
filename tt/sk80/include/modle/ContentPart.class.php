<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ContentPart extends Record
{
    const TABLE_NAME = 'content_part';

    public $id;
    public $content;

    public static function findById($id) {
        return self::findOneFrom('ContentPart', "id = '?'", array($id));
    }

    public static function deleteId($id)
    {
        self::deleteAttchment($id);
        return Record::delete('ContentPart', (int)$id);
    }

    public static function deleteAttchment($id)
    {
        $result = self::findById($id);
        $html = $result->content;
        $pattern = "/[src|href]=\"\/data\/upload\/(.*)\"[ http| target| src| title| width| height| alt| align| style| border| hspace| vspace|>]?/iU";
        if(preg_match_all($pattern, $html, $attachment, PREG_SET_ORDER)) {
            if(count($attachment)>0) {
                $path = DATA_DIR.'upload/';
                foreach($attachment as $file) {
                    if(file_exists($path.$file[1]))
                        @unlink($path.$file[1]);
                }
            }
        }
    }
}

?>
