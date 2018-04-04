<?php
/**
 * Tag info
 *
 * @copyright      The XOOPS project https://xoops.org/
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author         Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since          1.00
 * @package        module::tag
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Get item fileds:
 * title
 * content
 * time
 * link
 * uid
 * uname
 * tags
 *
 * @var array $items associative array of items: [modid][catid][itemid]
 *
 * @return boolean
 *
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

if (!function_exists($GLOBALS['artdirname'] . '_tag_iteminfo')):

    //get hotel tag function
    /**
     * @param $items
     * @return bool
     */
    function martin_tag_iteminfo(&$items)
    {
        if (empty($items) || !is_array($items)) {
            return false;
        }

        $items_id = [];
        foreach (array_keys($items) as $cat_id) {
            // Some handling here to build the link upon catid
            // catid is not used in article, so just skip it
            foreach (array_keys($items[$cat_id]) as $item_id) {
                // In article, the item_id is "art_id"
                $items_id[] = (int)$item_id;
            }
        }
        $itemHandler = xoops_getModuleHandler('hotel', 'martin');
        $items_obj   = $itemHandler->getObjects(new \Criteria('hotel_id', '(' . implode(', ', $items_id) . ')', 'IN'), true);

        foreach (array_keys($items) as $cat_id) {
            foreach (array_keys($items[$cat_id]) as $item_id) {
                if (!$item_obj = $items_obj[$item_id]) {
                    continue;
                }
                $items[$cat_id][$item_id] = [
                    'title'   => $item_obj->getVar('hotel_name'),
                    //"uid"        => $item_obj->getVar("uid"),
                    'link'    => "short.php?hotel_id={$item_id}",
                    'time'    => $item_obj->getVar('hotel_add_time'),
                    'tags'    => tag_parse_tag($item_obj->getVar('hotel_tags', 'n')),
                    'content' => '',
                ];
            }
        }
        unset($items_obj);
    }

    /**
     * Remove orphan tag-item links
     *
     * @param $mid
     * @return void
     */
    function martin_tag_synchronization($mid)
    {
        $itemHandler = xoops_getModuleHandler('article', $GLOBALS['artdirname']);
        $linkHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Link'); //@var \XoopsModules\Tag\Handler $tagHandler

        /* clear tag-item links */
        if ($linkHandler->mysql_major_version() >= 4):
            $sql = "    DELETE FROM {$linkHandler->table}"
                   . '    WHERE '
                   . "        tag_modid = {$mid}"
                   . '        AND '
                   . '        ( tag_itemid NOT IN '
                   . "            ( SELECT DISTINCT {$itemHandler->keyName} "
                   . "                FROM {$itemHandler->table} "
                   . "                WHERE {$itemHandler->table}.art_time_publish > 0"
                   . '            ) '
                   . '        )'; else:
            $sql = "    DELETE {$linkHandler->table} FROM {$linkHandler->table}"
                   . "    LEFT JOIN {$itemHandler->table} AS aa ON {$linkHandler->table}.tag_itemid = aa.{$itemHandler->keyName} "
                   . '    WHERE '
                   . "        tag_modid = {$mid}"
                   . '        AND '
                   . "        ( aa.{$itemHandler->keyName} IS NULL"
                   . '            OR aa.art_time_publish < 1'
                   . '        )';
        endif;
        if (!$result = $linkHandler->db->queryF($sql)) {
            //xoops_error($linkHandler->db->error());
        }
    }

endif;
