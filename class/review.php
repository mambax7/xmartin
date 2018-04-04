<?php

/**
 *
 * @access    public
 * @return void
 * @copyright 1997-2010 The Lap Group
 * @author    Martin <china.codehome@gmail.com>
 * @created   time :2010-07-22 16:27:07
 * */
class MartinReview extends XoopsObject
{
}

/**
 *
 * @access    public
 * @return void
 * @copyright 1997-2010 The Lap Group
 * @author    Martin <china.codehome@gmail.com>
 * @created   time :2010-07-22 16:27:25
 * */
class MartinReviewHandler extends XoopsObjectHandler
{
    /**
     * @create    cart object
     * @license   http://www.blags.org/
     * @created   :2010年07月04日 12时59分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function &create()
    {
        $obj = newMartinReview;

        return $obj;
    }

    /**
     * insert review for hotel
     * @access    public
     * @param $Data
     * @copyright 1997-2010 The Lap Group
     * @author    Martin <china.codehome@gmail.com>
     * @created   time :2010-07-22 16:28:18
     */
    public function saveReview($Data)
    {
        global $xoopsDB;
        if (!$Data) {
            return $Data;
        }
        $table = $xoopsDB->prefix('martin_user_review');
        $exist = $this->checkReviewExist($Data['hotel_id'], $Data['uid']);
        if (is_array($Data)) {
            foreach ($Data as $key => $value) {
                $v         .= $prefix . $value;
                $k         .= $prefix . $key;
                $updateStr .= $prefix . "$key = $value";
                $prefix    = ',';
            }
        }
        $sql = $exist ? "UPDATE $table SET %s WHERE hotel_id = {$Data['hotel_id']} AND uid = {$Data['uid']}" : "INSERT INTO $table (%s) VALUES (%s)";
        $sql = $exist ? sprintf($sql, $updateStr) : sprintf($sql, $k, $v);

        //echo $sql;
        return $xoopsDB->query($sql);
    }

    /**
     *
     * @access    public
     * @param $hotel_id
     * @param $uid
     * @return bool
     * @copyright 1997-2010 The Lap Group
     * @author    Martin <china.codehome@gmail.com>
     * @created   time :2010-07-22 16:43:23
     */
    public function checkReviewExist($hotel_id, $uid)
    {
        global $xoopsDB;
        $sql = "SELECT * FROM {$xoopsDB->prefix('martin_user_review')} WHERE hotel_id = $hotel_id AND uid = $uid ";

        return is_array($xoopsDB->fetchArray($xoopsDB->query($sql)));
    }

    /**
     *
     * @access    public
     * @param $hotel_id
     * @copyright 1997-2010 The Lap Group
     * @author    Martin <china.codehome@gmail.com>
     * @created   time :2010-07-22 16:49:41
     * @return array|false
     */
    public function getReview($hotel_id)
    {
        global $xoopsDB, $xoopsUser;
        $uid = $xoopsUser->uid();
        $sql = "SELECT * FROM {$xoopsDB->prefix('martin_user_review')} WHERE hotel_id = $hotel_id AND uid = $uid ";

        return $xoopsDB->fetchArray($xoopsDB->query($sql));
    }

    /**
     * get hotel review
     * @access    public
     * @param $hotel_id
     * @copyright 1997-2010 The Lap Group
     * @author    Martin <china.codehome@gmail.com>
     * @created   time :2010-07-22 17:03:32
     * @return array|false
     */
    public function getHotelReview($hotel_id)
    {
        global $xoopsDB;
        $table                  = $xoopsDB->prefix('martin_user_review');
        $sql                    = "SELECT avg(review_type_avg) as review_type_avg , count(uid) as count FROM $table WHERE hotel_id = $hotel_id GROUP BY hotel_id ";
        $row                    = $xoopsDB->fetchArray($xoopsDB->query($sql));
        $row['review_type_avg'] = round($row['review_type_avg'], 2);
        $row['score']           = (int)(($row['review_type_avg'] / 5) * 100);

        //var_dump($row);
        return $row;
    }
}
