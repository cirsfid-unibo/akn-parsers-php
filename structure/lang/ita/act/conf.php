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

	"preamble" => "/(?<=\>)({{titleList}}|{{chapterList}}|{{articleList}})\s*({{number}})|{{preambleEndList#i}}/m",
	"roman" => "(M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3}))",
    "number" => "\d+|{{roman}}+",
	"titleList" => Array("Titolo", "TITOLO"),
    "chapterList" => Array("Capo","Capitolo","CAPO"),
    "articleList" => Array("Art\.?","Articolo","ART\.?"),
    
	"preambleInitList" => Array(
							   "La\s+Camera\s+dei\s+deputati\s+ed\s+il\s+Senato\s+della\s+Repubblica\s+hanno\s+approvato;\s+IL\s+PRESIDENTE\s+DELLA\s+REPUBBLICA",
							   "IL\s+MINISTRO\s+DEL\s+LAVORO\s+E\s+DELLE\s+POLITICHE\s+SOCIALI\s+di\s+concerto\s+",
							   "IL\s+PRESIDENTE\s+DELLA\s+REPUBBLICA",
							   "IL DIRETTORE GENERALE\s+DEL TESORO",
							   "IL MINISTRO DELLA GIUSTIZIA",
							   "Attesto che",
							   "Il [Cc]onsiglio regionale ha approvato\.?",
							   "omissis",
							   //"Il\s+Presidente\s+della\s+Repubblica\s*",
							   ),
    
	"preambleEndList" => Array (
								"\s*E\s*m\s*a\s*n\s*a\s+((I|i)l seguente decreto legislativo|(I|i)l\s*seguente\s*(R|r)egolamento|(L|l)a +seguente +(L|l)egge|(i|I)l +seguente +decreto(-| +)legge):?",
								"EMANA\s+((I|i)l seguente decreto legislativo|(I|i)l\s*seguente\s*(R|r)egolamento|(l|L) +seguente +(L|l)egge|(i|I)l +seguente +decreto(-| +)legge):?",
								"(P|p)(romulga|ROMULGA)\s+((I|i)l seguente decreto legislativo|(I|i)l\s*seguente\s*(R|r)egolamento|(l|L)a +seguente +(L|l)egge|LA +SEGUENTE +LEGGE|(i|I)l +seguente +decreto(-| +)legge):?",
								"Attesto che",
								"Decreta:?",
								),
			   
    "conclusionsInitList" => Array(
    							   "Il\s+presente\s+decreto,?",
								   "La\s+presente\s+legge,",
								   "Il +presente +decreto +sar.{1,2} +pubblicato +nella +Gazzetta +Ufficiale +della +Repubblica +italiana.",
								   "La +presente +legge +regionale +sarà +pubblicata +nel +Bollettino +Ufficiale +della +Regione.",
								   "Data a Roma,?",
								   "Roma,"
								   )

);
?>