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

class CRParser {

    public $lang;
    private $patterns = array();

    public function __construct($lang) {
        $this->lang = $lang;
        $this->loadConfiguration($lang);
        $this->blacklist = Array(
        	" Whereas:  ",
        	"THE EUROPEAN PARLIAMENT AND THE COUNCIL OF THE EUROPEAN UNION",
        	"HAVE ADOPTED THIS REGULATION:",
        	);
    }


    public function loadConfiguration() {
        global $patterns;

		$digit = "(?:\d+[-]?\w*)";
		$roman = "(?=[MDCLXVI])M*(C[MD]|D?C{0,3})(X[CL]|L?X{0,3})(I[XV]|V?I{0,3})";

		$number = $digit;

		$patterns = array(
			"recital" => "/(?P<num>\($number\))\s*\b[A-Z][a-z]*\b/",
		);

		if (isset($localpatterns)) {
			$patterns = array_merge($localpatterns,$patterns);
		}

        $this->patterns = $patterns;
    }


    public function parse($content, $jsonOutput = FALSE) {
        $return = array();
		$preg_result = array();
		$element = array();
		$success = false ;
		while (!$success && $element = each($this->patterns)) {
			$success = 	preg_match_all($element['value'], $content, $n) ;
		}

		if ($success) {
				$return['recitals']=array();
				$tmpResult = array();
				$recitals = $n['0'];
				$recitals_nums = $n['1'];
				for($i=0;$i<count($recitals);$i++){
					$tmp = array();
					$recital = $recitals[$i];
					$recitals_num = $recitals_nums[$i];
					$recital_pos = strpos($content,$recital);
					$recital_str = '';
					if($i+1<count($recitals)){
						$nextPos = strpos($content,$recitals[$i+1]);
						$len = $nextPos-$recital_pos;
						$recital_str = substr($content,$recital_pos,$len);
						$tmp['len'] = $nextPos-$recital_pos;
					}else{
						$recital_str = substr($content,$recital_pos, -strlen($this->blacklist[2]));
					}
					$tmp['str'] = $recital_str;
					$tmp['pos'] = $recital_pos;
					$tmp['recital'] = $recital;

					$tmpResult[] = $tmp;

					$return['recitals'][] = array("num" => $recitals_num, "str" => $recital_str);
					$return['recitals_intro'] = $this->blacklist[0];
				}

				$citations = substr($content,0,$tmpResult[0]['pos']);
				if($citations != ""){
					$return['citations'] = explode(",",$citations);
					if ($return['citations'][0] == $this->blacklist[1])
    					$return['citations'] = array_slice($return['citations'], 1);
    				if ($return['citations'][sizeof($return['citations'])-1] == $this->blacklist[0])
    					$return['citations'] = array_slice($return['citations'], 0, -1);
    				foreach ($return['citations'] as $key => $value) $return['citations'][$key] = $value . ',';
				}
		}

        $ret = array("response" => $return);
        if($jsonOutput) {
            return json_encode($ret);
        } else {
            return $ret;
        }
    }
}

?>
