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

$rules = Array(

/*

	"references" => Array(0,1,2),

	0 => "/(?P<fragment>{{partnum}})/",
	1 => "/(?P<fragment>(?:{{partition}}\s+(?:{{num}}(?:,\ |\ and\ )?){1,3}(?:\ of\ )?){1,2})/",
	2 => "/(?P<fragment>(?:{{partition}}\s+(?:{{num}}(?:,\ |\ and\ )?){1,3}(?:\ of\ )?){1,2}{{type}})/",

	"partition" => Array("[Aa]rticle",
		                 "paragraphs?",
		                 ),

	"partnum"  => "(?:{{partition}}\s+{{num}}(?:\ of\ )?){1,2}",
	"num"      => "(?:\b[a-z]{1,2}\b\))|(?:\b[0-9]{1,4}\b(?:\.[0-9]+)?\)?[\-\–]?)|(?:{{roman}})",
	"roman" => "\b[IVX]+\b",

	"partition_type" => Array("article" => Array("[Aa]rticle"))
*/


	"references" => Array(0,6,1,2,5,3,4),

	0 => "/(?P<fragment>{{partition}} {{num}})/",
	6 => "/(?P<fragment>[Aa]rticle {{num}} of {{type}})/",
	1 => "/(?P<fragment>paragraph \d+ of [Aa]rticle {{num}})/",
	2 => "/(?P<fragment>paragraphs \d+ and \d+ of [Aa]rticle {{num}})/",
	5 => "/(?P<fragment>paragraphs \d+ and \d+ of this [Aa]rticle)/",
	3 => "/(?P<fragment>paragraphs \d+, \d+ and \d+ of [Aa]rticle {{num}})/",
	4 => "/(?P<fragment>[Aa]rticle {{num}}, paragraph \d+ of {{type}})/",


	"partnum"  => "(?:{{partition}}\s+{{num}}(?:\ of\ )?){1,2}",
	"roman" => "\b[IVX]+\b",

	"partition" => Array("[Aa]rticle",
		                 "paragraphs?",
		                 ),

	"num"      => "(?:\b[a-z]{1,2}\b\))|(?:\b[0-9]{1,4}\b(?:\.[0-9]+)?\)?[\-\–]?)|(?:{{roman}})",
	"roman" => "\b[IVX]+\b",

	"partition_type" => Array("article" => Array("[Aa]rticle")),

);
?>
