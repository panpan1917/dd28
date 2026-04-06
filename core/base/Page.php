<?php
if (!defined('KKINC')) exit('Request Error!');
class page {

    private $lang_style = array();

    function __construct() {
        $this->set_lang_style();
    }

    /* =====================
     * 设置文字样式
      ====================== */
    function set_lang_style() {
        $this->lang_style = array(
        1 => array('index' => '首页', 'end' => '尾页', 'next' => '下一页', 'pre' => '上一页'),
        2 => array('index' => '<<', 'end' => '>>', 'next' => '>', 'pre' => '<'),
        3 => array('index' => 'index', 'end' => 'last', 'next' => 'next', 'pre' => 'pre'),
        );
    }

    /* =========================
     * @分页列表
     * page int 当前页 *
     * total int 总数 *
     * pagesize 分页数 *
     * url 链接
     * pagetable 页码表数目 1 2 3 4 5 6
     * showselect 页面列表
     * style 1(首页)  2(<<)
     * @return string
      ========================= */
    function pagelist($str) {
        parse_str($str, $arr);
        if ($GLOBALS['k_static'] && !$arr['nostatic']) {
            return $this->pagelist_static($str);
        }
        $page = $arr['page'] ? $arr['page'] : 1;
        $pagesize = $arr['pagesize'] ? $arr['pagesize'] : 0;
        $pagetable = $arr['pagetable'] ? $arr['pagetable'] : 8;
        $showselect = $arr['showselect'] ? $arr['showselect'] : 0;
        $showtext = $arr['showtext'] ? $arr['showtext'] : 0;
        $style = $arr['style'] ? $arr['style'] : 1;
        $style = $this->lang_style[$style];
        //页码计算：
        $lastpg = ceil($arr['total'] / $pagesize); //最后页，也是总页数
        $page = min($lastpg, $page);
        $prepg = $page - 1; //上一页
        $lastpgextpg = $page == $lastpg ? 0 : $page + 1; //下一页

        $string = "";

        //开始分页导航条代码：
        if ($showtext == 1) {
            $string = '<span class="nums">共<em>' . $lastpg . '</em>页<em>' . $totle . '</em>条</span>';
        }
        //如果只有一页则跳出函数：
        if ($lastpg <= 1) return false;

        $url = $arr['url'] ? $arr['url'] : $_SERVER["REQUEST_URI"];


        //URL分析：
        $parse_url = parse_url($url);
        $url_query = $parse_url["query"]; //单独取出URL的查询字串
        if ($url_query) {
            //因为URL中可能包含了页码信息，我们要把它去掉，以便加入新的页码信息。
            //这里用到了正则表达式，请参考"PHP中的正规表达式"
            $url_query = preg_replace("#(^|&)page=$page#", "", $url_query);

            //将处理后的URL的查询字串替换原来的URL的查询字串：
            $url = str_replace($parse_url["query"], $url_query, $url);

            //在URL后加page查询信息，但待赋值：
            $url.=$url_query ? '&page=' : 'page=';
        } else {
            if(!$arr['url'])
            $url.="?page=";
        }
        if($arr['url'])$houchuo='.html';
        $string.=$prepg ? '<a href="' . $url . '1'.$houchuo.'">' . $style['index'] . '</a>' : '<span class="disabled">' . $style['index'] . '</span>';
        $string.=$prepg ? '<a href="' . $url . '' . $prepg.$houchuo . '">' . $style['pre'] . '</a>' : '<span class="disabled">' . $style['pre'] . '</span>';
        if ($pagetable > 1) {
            $u = ceil($pagetable / 2);//根据$pagetable计算单侧页码宽度$u
            $f = $page - $u;//根据当前页$currentPage和单侧宽度$u计算出第一页的起始数字
            //str_replace('{p}',,$fn)//替换格式
            if ($f < 0) {
                $f = 0;
            }//当第一页小于0时，赋值为0
            if ($lastpg < 1) {
                $lastpg = 1;
            }//当总数小于1时，赋值为1
            if ($page == 1) {
                $string.='<span class="current">1</span>';
            } else if ($page >= 5) {
                
            } else {
                $string.='<a href="' . $url . '1'.$houchuo.'">1</a>';
            }
            ///////////////////////////////////////
            for ($i = 1; $i <= $pagetable; $i++) {
                //echo $i;
                if ($lastpg <= 1) {
                    break;
                }//当总页数为1时
                $c = $f + $i;//从第$c开始累加计算
                if ($c == 1) {
                    continue;
                }
                if ($c == $lastpg) {
                    break;
                }
                if ($c == $page) {
                    $string.='<span class="current">' . $page . '</span>';
                } else {
                    $string.='<a href="' . $url . '' . $c .$houchuo. '">' . $c . '</a>';
                }
                if ($i > $lastpg) {
                    break;
                }//当总页数小于页码表长度时	
            }
            if ($page == $lastpg && $lastpg != 1) {
                $string.='<span class="current">' . $lastpg . '</span>';
            } else if ($page <= $lastpg - 5) {
                
            } else {
                $string.='<a href="' . $url . '' . $lastpg .$houchuo. '">' . $lastpg . '</a>';
            }
        }

        $string.=$lastpgextpg ? '<a href="' . $url . '' . $lastpgextpg .$houchuo. '">' . $style['next'] . '</a>' : '<span class="disabled">' . $style['next'] . '</span>';

        $string.=$lastpgextpg ? '<a href="' . $url . '' . $lastpg .$houchuo. '">' . $style['end'] . '</a>' : '<span class="disabled">' . $style['end'] . '</span>';

        if ($showselect == 1) {
            //下拉跳转列表，循环列出所有页码：
            $string.='跳至<select name="topage" size="1" onchange=\'window.location="' . $url . '"+this.value\'>';
            for ($i = 1; $i <= $lastpg; $i++) {
                if ($i == $page) {
                    $string.='<option value="' . $i . '" selected>' . $i . '</option>';
                } else {
                    $string.='<option value="' . $i . '">' . $i . '</option>';
                }
            }
            $string.='</select>页';
        }
        return $string;
    }

    function pagelist_static($str) {
        global $tid;
        parse_str($str, $arr);
        $page = $arr['page'] ? $arr['page'] : 1;
        $pagesize = $arr['pagesize'] ? $arr['pagesize'] : 0;
        $pagetable = $arr['pagetable'] ? $arr['pagetable'] : 8;
        $showselect = $arr['showselect'] ? $arr['showselect'] : 0;
        $showtext = $arr['showtext'] ? $arr['showtext'] : 0;
        $style = $arr['style'] ? $arr['style'] : 1;
        $style = $this->lang_style[$style];
        //页码计算：
        $lastpg = ceil($arr['total'] / $pagesize); //最后页，也是总页数
        $page = min($lastpg, $page);
        $prepg = $page - 1; //上一页
        $lastpgextpg = $page == $lastpg ? 0 : $page + 1; //下一页
        $firstcount = ($page - 1) * $pagesize;
        //开始分页导航条代码：
        $string = $showtext == 1 ? '<span class="nums">共<em>' . $lastpg . '</em>页<em>' . $totle . '</em>条</span>' : "";
        //如果只有一页则跳出函数：
        if ($lastpg <= 1) return false;
        $art = new arctype();
        $string.=$prepg ? '<a href="' . U('list',array('tid'=>$tid)) . '">' . $style['index'] . '</a><a href="' . U('list',array('tid'=>$tid,'page'=> $prepg)) . '">' . $style['pre'] . '</a>' : '<span class="disabled">' . $style['index'] . '</span><span class="disabled">' . $style['pre'] . '</span>';
        if ($pagetable > 1) {
            $u = ceil($pagetable / 2);//根据$pagetable计算单侧页码宽度$u
            $f = $page - $u;//根据当前页$currentPage和单侧宽度$u计算出第一页的起始数字
            //str_replace('{p}',,$fn)//替换格式
            if ($f < 0) {
                $f = 0;
            }//当第一页小于0时，赋值为0
            if ($lastpg < 1) {
                $lastpg = 1;
            }//当总数小于1时，赋值为1
            if ($page == 1) {
                $string.='<span class="current">1</span>';
            } else if ($page >= 5) {
                
            } else {
                $string.='<a href="' . U('list',array('tid'=>$tid)) . '">1</a>';
            }
            ///////////////////////////////////////
            for ($i = 1; $i <= $pagetable; $i++) {
                if ($lastpg <= 1) {
                    break;
                }//当总页数为1时
                $c = $f + $i;//从第$c开始累加计算
                if ($c == 1) {
                    continue;
                }
                if ($c == $lastpg) {
                    break;
                }
                if ($c == $page) {
                    $string.='<span class="current">' . $page . '</span>';
                } else {
                    $string.='<a href="' . U('list',array('tid'=>$tid,'page'=>$c)) . '">' . $c . '</a>';
                }
                if ($i > $lastpg) {
                    break;
                }//当总页数小于页码表长度时	
            }
            if ($page == $lastpg && $lastpg != 1) {
                $string.='<span class="current">' . $lastpg . '</span>';
            } else if ($page <= $lastpg - 5) {
                
            } else {
                $string.='<a href="' . U('list',array('tid'=>$tid,'page'=>$lastpg)) . '">' . $lastpg . '</a>';
            }
        }

        $string.=$lastpgextpg ? '<a href="' . U('list',array('tid'=>$tid,'page'=>$lastpgextpg)) . '">' . $style['next'] . '</a><a href="' .U('list',array('tid'=>$tid,'page'=> $lastpg)) . '">' . $style['end'] . '</a>' : '<span class="disabled">' . $style['next'] . '</span><span class="disabled">' . $style['end'] . '</span>';
        if ($showselect == 1) {
            //下拉跳转列表，循环列出所有页码：
            $string.='跳至<select name="topage" size="1" onchange=\'window.location="' . U('list',array('tid'=>$tid,'page'=> $i)) . '"\'>';
            for ($i = 1; $i <= $lastpg; $i++) {
                if ($i == $page) {
                    $string.='<option value="' . U('list',array('tid'=>$tid,'page'=>$tid, $i)) . '" selected>' . $i . '</option>';
                } else {
                    $string.='<option value="' . U('list',array('tid'=>$tid,'page'=>$tid, $i)) . '">' . $i . '</option>';
                }
            }
            $string.='</select>页';
        }
        return $string;
    }

}