set Pathname="D:\wwwroot\seproject1"
Set WshShell = CreateObject("WScript.Shell" ) 
WshShell.Run chr(34) & "C:\Batch Files\ mycommands.bat" & Chr(34), 0 
Set WshShell = Nothing 

cd /d %Pathname%

php artisan schedule:run