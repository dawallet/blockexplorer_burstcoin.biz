FOR /L %%A IN (1,1,10) DO (
  start cmd.exe /c D:\xampp\php\php.exe -f "D:\xampp\htdocs\bcdump\app\tasks\index.php"
  ping 127.0.0.1 -n 2 > nul
)