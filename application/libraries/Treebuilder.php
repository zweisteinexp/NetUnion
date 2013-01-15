<?php
/*
   HTML4.01 TR: http://www.w3.org/TR/html401
   for details, please refer to HTML 4.01 DTD http://www.w3.org/TR/html401/sgml/dtd.html

   TODO:
   - add parents stack while processing
   - play with <span> tags in firefox - they can be a block containers

 */
// elements marked as - 0 EMPTY in dtd

$pre = "pre ";
$list = "ul ol ";
$heading = "h1 h2 h3 h4 h5 h6 ";
$fontstyle = "tt i b big small ";
$phrase = "em strong dfn code samp kbd var cite abbr acronym ";
$special = "a img object br script map q sub sup span bdo ";
$formctrl = "input select textarea label button ";
$head_specific = "title base meta link "; // style is not head specific

$inline = $fontstyle.$phrase.$special.$formctrl;
$block = $heading.$list.$pre."dl div noscript blockquote form hr table fieldset address ";
$flow = $block.$inline;

$selft_closing_tags = tags_array("br hr input frame img area link col base basefont param meta embed spacer ");
#let's assume everything except $selft_closing_tags can accept %inline
$superflow = "div object ins del dd li fieldset button th td tr table html "; // according to DTD html and table don't accept %flow. Here is just as workaround
$superdata = "script style textarea title ";
$superblock = "body map blockquote form noscript ".$superflow.$superdata;
$superdata_arr = tags_array($superdata);
$superblock_arr = tags_array($superblock);
$superstruct_arr = tags_array("html body head ");
$head_specific_arr = tags_array($head_specific);
define('token_blank', " \t\r\n");

$validation = array( // kye_tag => possible tags under the key. It is almost the same as implicit open/close
		"frameset" => tags_array("frame noframes "),
		"dl" => tags_array("dt dd "),
		"ol" => tags_array("li "),
		"ul" => tags_array("li "),
		"select" => tags_array("optgroup option "),
		"optgroup" => tags_array("option "),
		"option" => tags_array(""),
		"table" => tags_array("a caption col colgroup thead tbody tfoot tr td input "),//actually, only input type='hidden'
		"tr" => tags_array("td th "),
		"thead" => tags_array("tr td "),
		"tbody" => tags_array("tr td "),
		"tfoot" => tags_array("tr td "),
		"colgroup" => tags_array("col "),
		"html" => tags_array("head body "),
		"head" => tags_array($head_specific."script style object "),
		);

$impossible_tags = array (
		// tbd - smth like "pre" => "object img ...",
		);

class Element {
	/*
	   there is no nodetype - if a child is a text node, it is stored as a string

	 */
	public $attr = array(); //attributes, ($name:$value,...)
	// public $tag = '';  // actually tag is just a '_tag' attribute in $this->attr
	public $parent = null;  // it is a tag
	public $children = array(); // children. Text nodes are just strings
	//CONSTANTS

	// BASIC METHODS    
	function attr($attr, $value = null) {
		$attr = strtolower($attr);
		if ($value !== null and $attr) $this->attr[$attr] = $value;
		return $this->attr[$attr];
	}

	function tag($tag = null) {
		if ($tag !== null) $this->tag = strtolower($tag);
		return $this->tag;
	}

	function look_down($keys) {
		if (is_string($keys)) $keys = array('_tag'=>$keys);
		foreach ($this->children as &$child)
			if ($this->same_class($child)) {
				$match = true;
				foreach ($keys as $key=>$value)
					if ($child->attr[$key] != $value) $match = false;
				if ($match) return $child;
				if ($found_node = $child->look_down($keys)) return $found_node;
			}
		return false;
	}

	function traverse($callback, $text_only=false) {
		// call_user_func($callback, $child);
		foreach ($this->children as &$child) {
			if (!$this->same_class($child) or !$text_only) call_user_func($callback, $child);
			if ($this->same_class($child)) $child->traverse($callback, $text_only);
		}
	}

	// STRUCTURE-MODIFYING METHODS
	function push_content() {
		$args = func_get_args();
		foreach ($args as &$arg) {
			array_push($this->children, $arg);
			if ($this->same_class($arg)) $arg->parent = &$this;
		}
	}

	function unshift_content() {
		$args = func_get_args();
		foreach ($args as &$arg) {
			array_unshift($this->children, $arg);
			if ($this->same_class($arg)) $arg->parent = &$this;
		}
	}

	function detach() {
		if (!isset($this->parent)) return;
		$i = $this->pindex();
		if ($i !== false) {
			array_splice($this->parent->children, $i, 1);
			unset($this->parent);
		}
	}

	function detach_content() {
		$children = $this->children;
		$this->children = array();
		foreach ($children as &$child)
			if ($this->same_class($child)) $child->parent = null;
		return $children;
	}

	function delete_content() {
		$children = $this->children;
		$this->children = array();
		foreach ($children as &$child)
			if ($this->same_class($child)) $child->__destruct();
		return $this;
	}

	function preinsert() {
		$args = func_get_args();
		$i = $this->pindex();
		if ($i !== false) {
			array_splice($this->parent->children, $i, 0, $args);
			foreach ($args as &$arg)
				if ($this->same_class($arg)) $arg->parent =&$this->parent;
		}
	}

	function postinsert() {
		$args = func_get_args();
		$i = $this->pindex();
		if ($i !== false) {
			array_splice($this->parent->children, ++$i, 0, $args); // ++i because it is POSTinsert
			foreach ($args as &$arg)
				if ($this->same_class($arg)) $arg->parent =&$this->parent;
		}
	}

	function seek_n_destroy($keys) { // it's not in original HTML::Element specs. I've created it just for my convenience
		$node = $this->look_down($keys);
		if ($node) {
			$node->__destruct();
			return true;
		}
		return false;
	}

	// SECONDARY STRUCTURAL METHODS

	function pindex() {
		if ($this->parent and $this->parent->children)
			for ($i=0; $i<count($this->parent->children); $i++)
				if ($this->parent->children[$i] === $this) return $i;
		return false;
	}

	function left() {
		$i = $this->pindex();
		if ($i) return $this->parent->children[$i-1];
		return null;
	}

	function right() {
		$i = $this->pindex();
		if ($i !== false) return $this->parent->children[$i+1];
		return null;
	}

	// DUMPING METHODS
	function as_HTML() {
		return $this->__toString();
	}

	function as_text() {
		global $ret;
		$ret = "";
		$this->traverse(create_function('$text','global $ret; $ret .= $text;'), true);
		return $ret;
	}

	function __toString() {
		global $superblock_arr;
		global $require_explicit_close;
		global $selft_closing_tags;
		$ret = "";
		$prettify = false;
		if ($this->tag) {
			$ret .= "<".$this->tag;
			foreach ($this->attr as $attr=>$val)
				if ($attr != '_tag') $ret .= " $attr=\"".($val === true ? "" : $val).'"';
			if (($selft_closing_tags[$this->tag] or !count($this->children)) and !$superblock_arr[$this->tag])
				return $ret ." />".($prettify and $$this->tag != 'pre' ? "\n" : "");
			$ret .= ">".($prettify ? "\n" : "");
		}
		foreach ($this->children as &$node) $ret.= $node;
		if ($this->tag) return $ret."</".$this->tag.">".($prettify ? "\n" : "");
		return $ret;
	}

	// INTERNAL METHODS
	function __construct($tag='') {
		$this->attr['_tag'] = strtolower($tag);
		$this->tag = &$this->attr['_tag'];
	}

	function __destruct() {
		$this->detach();
		$this->clean();
	}

	function same_class($obj) {
		return is_a($obj, 'Element');
	}

	function clean() {
		foreach ($this->children as &$child) {
			if ($this->same_class($child)) $child->__destruct();
			$child = null;
		}
		$this->attr = array();
		$this->pos = 0;
		$this->children = array();
	}
}

class MY_Treebuilder extends Element {
	private $html = null;
	private $head = null;
	private $body = null;
	private $options = array();

	// INTERNAL METHODS
	function parse_content($content) {
		$this->clean();
		$this->doc = $content;
		$this->cursor = $this;
		$this->pos = 0;
		$this->size = strlen($content);
		global $selft_closing_tags;
		global $validation;
		global $superdata_arr;
		global $head_specific_arr;
		global $superblock_arr;
		global $superstruct_arr;

		while ($this->pos<$this->size) {
			if ($str = $this->copy_until('<')) { // ie text node found
				// TBD - add check cursor is not %preformatted

				// TBD - add a parsing option to compress spaces
				if ($str) $this->cursor->push_content($str);
				continue;
			}
			if ($this->char() != '<') continue;
			++$this->pos;
			$this->copy_skip();
			$tag = $this->copy_upto_charlist('>'.token_blank);
			// echo "\n<br>line: ".__LINE__.", position: ".(int)$this->pos.", char: ".$this->char().", tag: $tag, cursor attr: ".join(":", $this->cursor->attr);
			if ($tag[0] == '/') { // closing tag
				$tag = strtolower(substr($tag, 1));
				if ($selft_closing_tags[$tag]) continue; // ignore things like </img>, </hr> etc
				// ignore closing tags for html/head/body since they'll be closed implicitly - head with start of body, html/body  with the end of doc
				if (!$superstruct_arr[$tag])
					$this->cursor = $this->get_closed_parent($tag);
				++$this->pos;
				continue;
			}

			if (substr($tag, 0, 3) == '!--') {// HTML comments
				$this->pos -= (strlen($tag) - 3);  // Otherwise, <!--foo--> will give you a single tag !--foo--
				$tag = substr($tag, 0, 3);
				$str = $this->text_until($tag, '-->');
				$this->cursor->push_content($str);
				continue;
			}

			if ($tag[0] == '!') {// XML declarations, like <!DOCTYPE ..>
				$str = $this->text_until($tag, '>');
				$this->cursor->push_content($str);
				continue;
			}

			$tag = strtolower($tag);
			if ($superdata_arr[$tag]) {// tags including #PCDATA
				$node = new Element($tag);
				if (!$this->parse_attr($node)) {
					$node->push_content($this->copy_until("</$tag", true));
					$this->copy_until('>');
					++$this->pos;
				}
				if ($tag == 'script' and $this->options['skip_scripts']) $node->__destruct();
				elseif ($head_specific_arr[$tag]) $this->attach_to_head($node);// for <title>
				else $this->cursor->push_content($node);
				continue;
			}

			// opening tag to parse

			/* workaround for nested html/body/head tags: 
			   <head>-specific tags should be attached to existing <head>. 
			   For everything else - <html/head/body> found inside other html/body should be ignored.  */
			if ($tag == 'html' and $this->html) {$this->parse_attr($this->html); continue;}
			if ($tag == 'body' and $this->body) {$this->parse_attr($this->body); continue;}
			if ($tag == 'head' and $this->head) {$this->parse_attr($this->head); continue;}
			$node = new Element($tag);
			while ($this->cursor->parent // If found opening tag can't be placed under the current cursor - find first matching parent
					and (   ($validation[$this->cursor->tag] and !$validation[$this->cursor->tag][$tag])
						or ($superblock_arr[$tag] and !$superblock_arr[$this->cursor->tag]))) { //implicit close <font>...<div>..</div> -> <font>...</font><div>..</div>
				$this->cursor = $this->cursor->parent;
			}
			if ($head_specific_arr[$tag]) $this->attach_to_head($node); // <link/base/meta>
			else $this->cursor->push_content($node);
			if (!$this->parse_attr($node) and !$selft_closing_tags[$tag] and !$head_specific_arr[$tag]) {
				if ($tag == 'html') $this->html = $node;
				elseif ($tag == 'body') $this->body = $node;
				elseif ($tag == 'head') $this->head = $node;
				$this->cursor = $node;
			}
		}
		unset ($this->cursor);
		unset ($this->doc);
	}

	function parse_file($file) {return $this->parse_content(file_get_contents($file)); }
	function char() { return $this->doc[$this->pos];}

	protected function copy_until($str, $case_insensitive=false) {
		$old_pos = $this->pos;
		$new_pos = $case_insensitive ? stripos($this->doc, $str, $old_pos) : strpos($this->doc, $str, $old_pos);
		$this->pos = ($new_pos !== false) ? $new_pos : $this->size;
		return substr($this->doc, $old_pos, $this->pos - $old_pos);
	}

	protected function copy_upto_charlist($chars) {
		$old_pos = $this->pos;
		$len = strcspn ($this->doc, $chars, $old_pos);
		if ($len === false) ($len = $this->size - $this->pos); // ie if nothing found - copy up to the end
		$this->pos += $len;
		return (string) substr($this->doc, $old_pos, $len);
	}

	protected function copy_matching_charlist($chars) {
		$old_pos = $this->pos;
		$len = strspn ($this->doc, $chars, $old_pos);
		$this->pos += $len;
		return substr($this->doc, $old_pos, $len);
	}

	protected function text_until($tag, $end_marker) { // this is a special function to get the whole content of <!DOCTYPE>, <script> or <!-- --> into a text node
		$str = "<".$tag.$this->copy_until($end_marker).$end_marker;
		$this->pos += strlen($end_marker);
		return $str;
	}

	protected function copy_skip() {
		return $this->copy_matching_charlist(token_blank);
	}


	function parse_attr(&$node) { // returns True if tag closed self: <img src='foo_pic.jpg' />
		while ($this->char() != '>'
				and $this->char() != '<' // handle <tr <td>hello</td>
				and $this->pos<$this->size) {
			// echo "\n<br> line ".__LINE__.", pos: ".$this->pos.", char: ".$this->char().", name: $name, value: $val";
			$this->copy_matching_charlist("'\"".token_blank);
			/* HTML dtd does not define any convention for attr namespaces. All the defined attributes are only A-Za-z and hyphen.
			   However, real browser (FF3) accepts all characters except <>=\\. Please check malformed attrN tests for details
			 */
			$name = rtrim($this->copy_upto_charlist('=<>"\''.token_blank), "'\"-"); // attr can't neither start nor end with hyphen
			$name = strtolower($name);
			$ret = ($name == '/'); // tag should be considered closed only when slash is right before the end. <a href='foo' / alt='link'> is not 
			if ($ret) continue;
			if ($this->doc[$this->pos - 1] == '-') $this->doc[--$this->pos] = '=';
			$this->copy_matching_charlist("'\"".token_blank);
			if ($this->char() == '=') {// parse attr value. Should support missing quotes: <table border=1>
				++$this->pos;
				$this->copy_skip();
				$quot = $this->char();
				switch ($quot) {
					case '"':
					case "'":
						$quot = $this->char();
						++$this->pos;
						$val = $this->copy_upto_charlist($quot."<>");
						++$this->pos;
						if ($quot == "'") $val = str_replace('"', "&quot;", $val);
						break;
					default:
						$val = $this->copy_upto_charlist('/<>\'"'.token_blank); // actually, only A-Za-z0-9.:- are allowed by HTML dtd without quotes
						if ($val === "") $val = true;
				}
			}
			else { // options like <input type='checkbox' checked> - the attr['checked'] would be true in this case
				$val = true;
			}
			if (    $node // note, the tag may be ignored and null will be passed instead of node. In this case, parse_attr should be called to read attrs
					and $name) $node->attr($name, $val);
			if ($this->char()=='>' or $this->char() == '<') break;
		}
		$this->copy_upto_charlist('><');
		if ($this->char() == '>') ++$this->pos;
		return isset($ret) ? $ret : false ;
	}

	function get_closed_parent($tag) {
		global $superblock_arr;
		global $superstruct_arr;
		global $validation;
		/* how to select first matching parent:
		   1) matching opening tag;
		   2) closing tag is inline, but the parent is block/flow - just ignore the closing tag
		   3) reached <body|html|head> - ignore
		   4) parent tag implicitly opens/closes found closing tag
		   5) reached root - create a single node and delegate children
		 */
		//  echo "\n<br>line: ".__LINE__.", position: ".(int)$this->pos.", char: ".$this->char().", tag: $tag, cursor tag: ".$this->cursor->tag;
		$cursor = $this->cursor;
		if ($superstruct_arr[$cursor->tag]) return $this->cursor;    // 3
		while ($cursor->parent) {
			if ($cursor->tag == $tag) return $cursor->parent; // 1
			if ($superblock_arr[$cursor->tag] and !$superblock_arr[$tag]) return $this->cursor;    //2
			if ($superstruct_arr[$cursor->parent->tag]) return $this->cursor;    // 3
			if ($validation[$cursor->tag] and $validation[$cursor->tag][$tag]) break; //4
			$cursor = $cursor->parent;
		}

		// root reached - 5
		$node = new Element($tag);
		$node->children = $cursor->children;
		$cursor->children = array(&$node);
		return $cursor;
	}

	function attach_to_head(&$node) {
		if (!$this->head) {
			$this->head = new Element('head');
			if (!$this->html) {
				$this->html = new Element('html');
				$this->html->children = $this->children;
				$this->children = array($this->html);
			}
			$this->html->push_content($this->head);
		}
		return $this->head->push_content($node);
	}

	function __construct($options = array()) {
		$this->options = $options;
	}

	function __destruct() {
		unset($this->html);
		unset($this->body);
		unset($this->head);
		parent::__destruct();
	}    

	function __toString() {
		return parent::__toString($this->options['prettify']);
	}
}

function tags_array($str) {
	$arr = explode(" ", $str);
	return array_combine($arr, $arr);
}
?>
