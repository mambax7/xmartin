<?php

namespace XoopsModules\Xmartin;

/**
 * @get       martin hotel news handler
 * @license   http://www.blags.org/
 * @created   :2010年06月29日 20时38分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
class NewsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @get       rows
     * @license   http://www.blags.org/
     * @created   :2010年06月20日 13时09分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param        $sql
     * @param null   $key
     * @return array
     */
    public function getRows($sql, $key = null)
    {
        global $xoopsDB;
        $result = $xoopsDB->query($sql);
        $rows   = [];
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            if (null === $key) {
                $rows[] = $row;
            } else {
                $rows[$row[$key]] = $row;
            }
        }

        return $rows;
    }

    /**
     * @get       hotel news
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年06月29日 20时38分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $artids
     * @return array|string
     */
    public function getHotelNews($artids)
    {
        global $xoopsDB;
        if (empty($artids) || !is_array($artids)) {
            return $artids;
        }
        $artids    = implode(',', $artids);
        $sql       = 'SELECT text_id,alias FROM ' . $xoopsDB->prefix('news_text') . " WHERE art_id IN ($artids)";
        $text_rows = $this->getRows($sql, 'text_id');

        $sql    = 'SELECT art_id,cat_alias,art_title,art_pages FROM ' . $xoopsDB->prefix('news_article') . "    WHERE art_id IN ($artids) ORDER BY art_time_publish DESC";
        $result = $xoopsDB->query($sql);
        $rows   = [];
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $text_id              = unserialize($row['art_pages']);
            $text_id              = $text_id[0];
            $url                  = $text_rows[$text_id]['alias'];
            $url                  = XOOPS_URL . $row['cat_alias'] . $url;
            $rows[$row['art_id']] = ['url' => $url, 'title' => $row['art_title']];
        }

        return $rows;
    }
}
