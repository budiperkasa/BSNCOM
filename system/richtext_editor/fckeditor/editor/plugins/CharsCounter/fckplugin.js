/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2004 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: fckplugin.js
 * 	This is the Chars Counter plugin definition file.
 * 
 * Version:  2.0 FC
 * Modified: 2005-05-25
 * 
 * File Authors:
 * 		Luigi Maniscalco (l.maniscalco@visioni.info)
 */
 
// Define the command.
var FCKCharsCounterCommand = function( maxlength, countername )
{
	this.Name = 'CharsCounter' ;
	this.TextLength = 0 ;
	this.HTMLLength = 0 ;
	this.CurrentKeyStroke = 0 ;
	this.MaxLength = maxlength ;
	this.LeftChars = maxlength ;
	this.CounterName = countername ;
}

FCKCharsCounterCommand.prototype.Execute = function()
{
	var DOMDocument = FCK.EditorDocument ;
	// The are two diffent browser specific ways to get the text.
	// I also use a trick to count linebreaks (<br>/</p>) as one-stroke.
	if ( FCKBrowserInfo.IsIE ) {
		var HTMLText = DOMDocument.body.innerHTML ;
		var linebreaks = HTMLText.length - HTMLText.replace(/<(br|\/p)/gi,'**').length;
		this.TextLength = DOMDocument.body.innerText.length + linebreaks;
		this.HTMLLength = HTMLText.length ;
	} else {
		var r = DOMDocument.createRange() ;
		r.selectNodeContents( DOMDocument.body ) ;
		var HTMLText = r.startContainer.innerHTML ;
		var linebreaks = HTMLText.length - HTMLText.replace(/<(br|\/p)/gi,'**').length;
		this.TextLength = r.toString().length + linebreaks;
		this.HTMLLength = HTMLText.length ;
	}
	// MaxLength is optional: if undefined, LeftChars is always 1
	this.LeftChars = this.MaxLength ? this.MaxLength - this.TextLength : 1 ;
	// Update values in the toolbar button, if defined.
	updateToolbar(this.LeftChars);
	if ( this.LeftChars <= 0 && this.CurrentKeyStroke != 8 && this.CurrentKeyStroke != 0) {
		//if you try to enter more chars, you can't but you can use DEL, BACKSPACE and ARROW KEYs
		return false;
	} else {
		// Update value in the external counter, if defined.
		this.Counter = FCK.LinkedField.form[this.CounterName];
		if ( this.Counter ) {
			this.Counter.value = this.LeftChars ;
		}
		//let the char be writen
		return true;
	}
}

FCKCharsCounterCommand.prototype.GetState = function()
{
	return FCK_TRISTATE_OFF ;
}

// Register the related command.
FCKCommands.RegisterCommand( 'CharsCounter', new FCKCharsCounterCommand( FCK.Config['MaxLength'], FCK.Config['CounterName'] ) ) ;

// Create the "CharsCounter" toolbar button.
var oCharsCounterItem = new FCKToolbarButton( 'CharsCounter', 'Left Chars: ' + FCK.Config['MaxLength'] , 'Left Chars', FCK_TOOLBARITEM_ONLYTEXT, false, true ) ;
FCKToolbarItems.RegisterItem( 'CharsCounter', oCharsCounterItem ) ;

function updateToolbar(LeftChars){	
	if(!document.all && LeftChars != 0)
		LeftChars++;
	FCK.ToolbarSet.ToolbarItems.LoadedItems.CharsCounter._UIButton.MainElement.innerHTML = '<table cellpadding="0" cellspacing="0"><tbody><tr><td><img src="images/spacer.gif" class="TB_Button_Padding"></td><td class="TB_Button_Text" nowrap="nowrap">Left Chars:' + LeftChars + '</td><td><img src="images/spacer.gif" class="TB_Button_Padding"></td></tr></tbody></table>';
}

function DENOnKeyDownFunction(key){
	FCKCommands.GetCommand( 'CharsCounter' ).CurrentKeyStroke = key;
	return FCKCommands.GetCommand( 'CharsCounter' ).Execute() ;
}

function DenIE_OnKeyDown(){
     var e = FCK.EditorWindow.event;
	 var r_val = DENOnKeyDownFunction(e.keyCode);
	 e.cancelBubble = !r_val ;
	 e.returnValue = r_val;
	 return r_val;
}

var DenGecko_OnKeyDown = function(e) {
	var r_val = DENOnKeyDownFunction(e.which);
	if(!r_val){
		e.preventDefault();
		e.stopPropagation();
	}
	return r_val
};

function FCKKeyPress_SetListeners() {
    if (document.all) {        // If Internet Explorer.
        FCK.EditorDocument.attachEvent("onkeydown", DenIE_OnKeyDown ) ;
    } else {                // If Gecko.
        FCK.EditorDocument.addEventListener( 'keypress', DenGecko_OnKeyDown, true ) ;
    }
}

//event for keystrokes in FCK
FCK.Events.AttachEvent( 'OnAfterSetHTML', FCKKeyPress_SetListeners ) ;

// First time execution.
FCKCommands.GetCommand( 'CharsCounter' ).Execute() ;
	
