Set objShell = WScript.CreateObject("WScript.Shell")
i = 1 : s = ""
while i < 600000
  s = s & i & ", "
  i = i+1
  objShell.Run("cmd.exe /c D:\xampp\php\php.exe -f D:\xampp\htdocs\bcdump\app\tasks\index.php -- action=cycle"), 0, True
wend