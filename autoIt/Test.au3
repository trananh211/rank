#cs ----------------------------------------------------------------------------

 AutoIt Version: 3.3.14.2
 Author:         myName

 Script Function:
	Template AutoIt script.

#ce ----------------------------------------------------------------------------

; Script Start - Add your code below here

#cs
ShellExecute("chrome.exe", "https://www.google.com.vn/?gws_rd=ssl","","");
#ce

#include <MsgBoxConstants.au3>

#include <StringConstants.au3>

#cs
; Replace a blank space (' ') with a - (minus) character.
Local $sString = StringReplace("This is a sentence with whitespace.", " ", "+")
Local $iReplacements = @extended
MsgBox($MB_SYSTEMMODAL, "", $iReplacements & " replacements were made and the new string is:" & @CRLF & @CRLF & $sString)
#ce

#include <File.au3>

#RequireAdmin


$des  = "D:\Sync G\MMO\API\test\result\"
IF DirGetSize($des) == -1 Then
   DirCreate($des)
EndIf

runProgram();

;_INetGetMHT( "https://www.google.com.vn/search?q=autoit", "C:\test.html" )

Func runProgram()

   $path = "https://www.google.com.vn/search?q=";
   $file = "D:\Sync G\MMO\API\vd.txt"

   FileOpen($file, 0)

   For $i = 1 to _FileCountLines($file)
	  $line1 = FileReadLine($file, $i)
	  $line = Call("trim",$line1)
	  If $line = '' Then
		 ContinueLoop
	  EndIf
	  Local $sString = Call("replaceString",$line)
	  Local $open = $path&$sString

	  ;Sleep(5000)
	  ;_INetGetMHT( $open, $des&$sString&".MHT" )

	  ;#cs ----------------Ham lay noi dung file html------------------
	  Local $a[4]
	  $a[0] = "CallArgArray"
	  $a[1] = $open
	  $a[2] = $line
	  $a[3] = $des
	  Call("getContent", $a)
	  ;#ce ----------------Ham lay noi dung file html------------------
   Next

   FileClose($file)
   ;Send("^w 2")
EndFunc

;ham trim ky tu
Func trim($str)
   ; Strip leading and trailing whitespace as well as the double spaces (or more) in between the words.
   Local $sString = StringStripWS($str, $STR_STRIPLEADING + $STR_STRIPTRAILING + $STR_STRIPSPACES)
   ;MsgBox($MB_SYSTEMMODAL, "", $sString)
   Return $sString
EndFunc

;ham lay noi dung url cho vao database
Func getContent($url,$title,$des)
   ShellExecute("chrome.exe", $url,"","")

   Local $window_title = $title&" - Google Search"
   ;; get window handle
   $window_handle = WinWait($window_title, "", 5)

   ;;cho 10s cho chrome no load
   Sleep(8000)
   Send("^s")

   Opt("WinTitleMatchMode", 4) ;1=start, 2=subStr, 3=exact, 4=advanced, -1 to -4=Nocase
   Opt("WinSearchChildren", 1) ;0=no, 1=search children also

   Local $hWnd = WinWait("Save As", "", 5)
   ; Activate the Notepad window using the handle returned by WinWait.
   WinActivate($hWnd)

   If Not @error Then
	  ControlClick("Save As", "", "[CLASS:Edit; INSTANCE:1]")
	  Sleep(1000)
	  ControlSetText("Save As", "", "[CLASS:Edit; INSTANCE:1]", $des&$window_title)
	  Sleep(1000)
	  ControlClick("Save As", "", "[CLASS:Button; INSTANCE:2]")
	  Sleep(1000)
   EndIf

   ;neu ton tai tep bao trùng lặp
   If WinExists("Confirm Save As") Then
	  Local $hWnd1 = WinWait("Confirm Save As", "", 5)
	  WinActivate($hWnd1)
	  Sleep(1000)
	  ControlClick("Confirm Save As", "", "[CLASS:Button; INSTANCE:1]")
   EndIf

   ; close the window
   ;WinClose($window_handle)
   Sleep(1000)
   ;WinActivate($window_title)
   Send("^w")
EndFunc


#cs
   Cach dung:
	  Local $aA[3]
	  $aA[0] = "CallArgArray" ; mac dinh luc nao cung phai khai bao mang
	  $aA[1] = $open
	  $aA[2] = $line
	  Call("getTest", $aA)
#ce

;Ham lay noi dung HTML cua 1 trang web gui vao
Func _INetGetMHT( $url, $file )
    Local $msg = ObjCreate("CDO.Message")
    If @error Then Return False
    Local $ado = ObjCreate("ADODB.Stream")
    If @error Then Return False

    With $ado
        .Type = 2
        .Charset = "US-ASCII"
        .Open
    EndWith
    $msg.CreateMHTMLBody($url, 0)
    $msg.DataSource.SaveToObject($ado, "_Stream")
    FileDelete($file)
    $ado.SaveToFile($file, 1)
    $msg = ""
    $ado = ""
    Return True
EndFunc

;ham lay ra cửa sổ window nao dang duoc active
Func getWinCurent()
   $title = WinGetTitle('')
   MsgBox(0, '', $title)
EndFunc

;ham thay convert tu khoảng trắng sang + cho google search
Func replaceString($keyword)
   Local $sString = StringReplace($keyword, " ", "+");
   Return $sString
EndFunc

;ham alert chuoi gui vao
Func alert($ms)
   MsgBox($MB_SYSTEMMODAL, "", $ms)
EndFunc
