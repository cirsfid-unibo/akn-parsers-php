<?php
/*
 * Copyright (c) 2014 - Copyright holders CIRSFID and Department of
 * Computer Science and Engineering of the University of Bologna
 *
 * Authors:
 * Monica Palmirani – CIRSFID of the University of Bologna
 * Fabio Vitali – Department of Computer Science and Engineering of the University of Bologna
 * Luca Cervone – CIRSFID of the University of Bologna
 *
 * Permission is hereby granted to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The Software can be used by anyone for purposes without commercial gain,
 * including scientific, individual, and charity purposes. If it is used
 * for purposes having commercial gains, an agreement with the copyright
 * holders is required. The above copyright notice and this permission
 * notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * Except as contained in this notice, the name(s) of the above copyright
 * holders and authors shall not be used in advertising or otherwise to
 * promote the sale, use or other dealings in this Software without prior
 * written authorization.
 *
 * The end-user documentation included with the redistribution, if any,
 * must include the following acknowledgment: "This product includes
 * software developed by University of Bologna (CIRSFID and Department of
 * Computer Science and Engineering) and its authors (Monica Palmirani,
 * Fabio Vitali, Luca Cervone)", in the same place and form as other
 * third-party acknowledgments. Alternatively, this acknowledgment may
 * appear in the software itself, in the same form and location as other
 * such third-party acknowledgments.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

require_once(dirname(__FILE__)."/../utils.php");
error_reporting(E_ERROR | E_PARSE);

class Paragraph {

    public $lang, $docType;
    private $parserRules = array();

    public function __construct($lang, $docType, $context) {
        $this->lang = $lang;
        $this->docType = $docType;
        $this->dirName = dirname(__FILE__);
        $this->context = $context;

        $this->loadConfiguration();
    }

    public function parse($content, $jsonOutput = FALSE) {
    	$return = array();
    	if($this->lang && $this->docType && !empty($this->parserRules)) {
    		$replacement = "/\n\${1}";
    		//// PREPARE: SPLIT PARAGRAPH ///////////////////////////////////
    		/////////////////////////////////////////////////////////////////
    		$pattern = "/(?<=[^n][\.:;]) (\w{1,2}(?:-\w+)?[\.\)])/u";
    		$content = preg_replace($pattern, $replacement, $content);
    		$pattern = "/(?<=[^n][\.:;]) (\(\(\w{1,2}(?:-\w+)?[\.\)][^\)])/u";
    		$content = preg_replace($pattern, $replacement, $content);
    		/////////////////////////////////////////////////////////////////
    		$pattern = "/(?<=[^n][\.:;]\)\)) (\w{1,2}(?:-\w+)?[\.\)])/u";
    		$content = preg_replace($pattern, $replacement, $content);
    		$pattern = "/(?<=[^n][\.:;]\)\)) (\(\(\w{1,2}(?:-\w+)?[\.\)][^\)])/u";
    		$content = preg_replace($pattern, $replacement, $content);
    		/////////////////////////////////////////////////////////////////
    		$pattern = "/(?<=\(\([1-9]\)\)) (\w{1,2}(?:-\w+)?[\.\)])/u";
    		$content = preg_replace($pattern, $replacement, $content);
    		$pattern = "/(?<=\(\([1-9][0-9]\)\)) (\w{1,2}(?:-\w+)?[\.\)])/u";
    		$content = preg_replace($pattern, $replacement, $content);
    		/////////////////////////////////////////////////////////////////
    		/////////////////////////////////////////////////////////////////
    		//echo $content;
    		$return = $this->parse_recursive($content, $this->context);
    	} else {
			$return = Array('success' => FALSE);
		}

        $ret = array("response" => $return);
        if($jsonOutput) {
            return json_encode($ret);
        } else {
            return $ret;
        }
    }

    private function parse_recursive($content, $hierarchyIndex) {
    	$return = array();
		$hierarchy = array_slice($this->parserRules["hierarchy"], $hierarchyIndex);
		foreach($hierarchy as $key => $value) {
			if(array_key_exists($value, $this->parserRules)) {
				$resolved = resolveRegex($this->parserRules[$value], $this->parserRules,
	                                     $this->lang,$this->docType, $this->dirName);

				$success = 	preg_match_all($resolved["value"], $content, $result, PREG_OFFSET_CAPTURE);
				if ($success) {
					$return[$value] = array();
					$offsets = array();
					$nums = array();
					$numsOffset = array();
					$headings = array();
					$headingsOffset = array();
					$values = array();
					$numitems = array();
					$numparagraphs = array();
					$bodyitems = array();

					//////////////////////////////////////////
					////////// PREPARE ///////////////////////

					foreach($result["0"] as $index => $match) {
						$values[] = $match[0];
						$offsets[] = $match[1];
						//print_r($result);
					}

					if(array_key_exists("num",$result)) {
						foreach($result["num"] as $index => $match) {
							$nums[] = $match[0];
							$numsOffset[] = $match[1];
						}
					}

					if(array_key_exists("heading",$result)) {
						foreach($result["heading"] as $index => $match) {
							if ( is_array($match) ) {
								$headings[] = $match[0];
								$headingsOffset[] = $match[1];
							}
						}
					}

					if(array_key_exists("numparagraph",$result)) {
						foreach($result["numparagraph"] as $index => $match) {
							$numparagraphs[] = $match[0];
						}
					}

					if(array_key_exists("numitem",$result)) {
						foreach($result["numitem"] as $index => $match) {
							$numitems[] = $match[0];
						}
					}

					if(array_key_exists("numitem1",$result)) {
						foreach($result["numitem1"] as $index => $match) {
							$numitems[] = $match[0];
						}
					}

					if(array_key_exists("numitem2",$result)) {
						foreach($result["numitem2"] as $index => $match) {
							$numitems[] = $match[0];
						}
					}

					if(array_key_exists("bodyitem",$result)) {
						foreach($result["bodyitem"] as $index => $match) {
							$bodyitems[] = $match[0];
						}
					}

					//////////////////////////////////////////
					////////// END PREPARE ///////////////////

					$pairs = arrayToPairsArray($offsets, strlen($content));
					for($i = 0; $i < count($pairs); $i++) {
						$pair = $pairs[$i];
						$valueArray = array();
						$subString = substr($content, $pair[0], ($pair[1]-$pair[0]));
						$valueArray["value"] = $values[$i];
						$valueArray["start"] = $offsets[$i];

					    if(array_key_exists($i,$nums)) {
							$numContent = array();
							$numContent["value"] = $nums[$i];
							$numContent["start"] = $numsOffset[$i];
							$valueArray["num"] = $numContent;
						}

						if(array_key_exists($i,$headings)) {
							$headingContent = array();
							$headingContent["value"] = $headings[$i];
							$headingContent["start"] = $headingsOffset[$i];
							$valueArray["heading"] = $headingContent;
						}
						if(array_key_exists($i,$numparagraphs)) {
							$valueArray["numparagraph"] = $numparagraphs[$i];
						}
						if(array_key_exists($i,$numitems)) {
							$valueArray["numitem"] = $numitems[$i];
						}
						if(array_key_exists($i,$bodyitems)) {
							$valueArray["bodyitem"] = trim($bodyitems[$i]);
						}
						$valueArray["contains"] = $this->parse_recursive($subString,$hierarchyIndex+$key+1);
						$return[$value][] = $valueArray;

					}
					break;
				}
			}
		}
		return $return;
    }

    private function loadConfiguration() {
        $this->parserRules = importParserConfiguration($this->lang,$this->docType, $this->dirName);
    }
}

?>
