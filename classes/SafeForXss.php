<?php
/**
 * 富文本编辑器本身有编码功能，所以下面这个类实际上用不到，仅只用于学习
 */
class SafeForXss{
	//------------------------------php防注入和XSS攻击通用过滤-----Start--------------------------------------------//
	public static function string_remove_xss($html) {
	    preg_match_all("/\<([^\<]+)\>/is", $html, $ms);
	 
	    $searchs[] = '<';
	    $replaces[] = '&lt;';
	    $searchs[] = '>';
	    $replaces[] = '&gt;';
	 
	    if ($ms[1]) {
	        $allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote';
	        $ms[1] = array_unique($ms[1]);
	        foreach ($ms[1] as $value) {
	            $searchs[] = "&lt;".$value."&gt;";
	 
	            $value = str_replace('&amp;', '_uch_tmp_str_', $value);
	            $value = self::string_htmlspecialchars($value,ENT_HTML5);
	            $value = str_replace('_uch_tmp_str_', '&amp;', $value);
	 
	            // $value = str_replace(array('\\', '/*'), array('.', '/.'), $value);//不明白为什么要替换
	            $skipkeys = array('onabort','onactivate','onafterprint','onafterupdate','onbeforeactivate','onbeforecopy','onbeforecut','onbeforedeactivate',
	                    'onbeforeeditfocus','onbeforepaste','onbeforeprint','onbeforeunload','onbeforeupdate','onblur','onbounce','oncellchange','onchange',
	                    'onclick','oncontextmenu','oncontrolselect','oncopy','oncut','ondataavailable','ondatasetchanged','ondatasetcomplete','ondblclick',
	                    'ondeactivate','ondrag','ondragend','ondragenter','ondragleave','ondragover','ondragstart','ondrop','onerror','onerrorupdate',
	                    'onfilterchange','onfinish','onfocus','onfocusin','onfocusout','onhelp','onkeydown','onkeypress','onkeyup','onlayoutcomplete',
	                    'onload','onlosecapture','onmousedown','onmouseenter','onmouseleave','onmousemove','onmouseout','onmouseover','onmouseup','onmousewheel',
	                    'onmove','onmoveend','onmovestart','onpaste','onpropertychange','onreadystatechange','onreset','onresize','onresizeend','onresizestart',
	                    'onrowenter','onrowexit','onrowsdelete','onrowsinserted','onscroll','onselect','onselectionchange','onselectstart','onstart','onstop',
	                    'onsubmit','onunload','javascript','script','eval','behaviour','expression','style','class');
	            $skipstr = implode('|', $skipkeys);//转化成可以被正则表达式利用的字符串
	            $value = preg_replace(array("/($skipstr)/i"), '.', $value);//将符合上述字符的字符替换为.号
	            //如果没有找到和allowtags相符合的字符串，就将value设为空
	            if (!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
	                $value = '';
	            }
	            $replaces[] = empty($value) ? '' : "<" . str_replace('&quot;', '"', $value) . ">";
	        }
	    }
	    $html = str_replace($searchs, $replaces, $html);
	 
	    return $html;
	}
	//php防注入和XSS攻击通用过滤 
	public static function string_htmlspecialchars($string, $flags = null) {
	    if (is_array($string)) {
	        foreach ($string as $key => $val) {
	            $string[$key] = string_htmlspecialchars($val, $flags);
	        }
	    } else {
	        if ($flags === null) {
	            $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
	            if (strpos($string, '&amp;#') !== false) {
	                $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
	            }
	        } else {
	            if (PHP_VERSION < '5.4.0') {
	                $string = htmlspecialchars($string, $flags);
	            } else {
	                if (!defined('CHARSET') || (strtolower(CHARSET) == 'utf-8')) {
	                    $charset = 'UTF-8';
	                } else {
	                    $charset = 'ISO-8859-1';
	                }
	                $string = htmlspecialchars($string, $flags, $charset);
	            }
	        }
	    }
	 
	    return $string;
	}
	
	//------------------php防注入和XSS攻击通用过滤-----End--------------------------------------------//
	
	/**
	 * 上述逻辑看上去比较复杂，而且会将一些特殊字符转化为空白字符，不符合项目的实际需要
	 * 需要在文章中使用源代码时，无法显示源码，所以我还是自己写代码实现相关逻辑
	 * 先全部替换为htmlspecialchars的编码，然后将符合要求的元素标记还原成原来的编码
	 */
	public static function onlyCodeXss($html){
		$html=htmlspecialchars($html);
		$allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote';
		$tagsArr=explode("|", $allowtags);
		foreach($tagsArr as $tag){
			$pattern="/(&lt;$tag&gt;)/i";
			preg_replace($pattern,"<$tag>",$html);
			$pattern="/(&lt;$tag\/&gt;)/i";
			preg_replace($pattern,"<$tag/>",$html);
		}		
		return $html;
	} 
	
}

?>