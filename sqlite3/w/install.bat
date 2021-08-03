@echo off
rem ----------------------------------------------------------------------------------------------------------
IF "%PROCESSOR_ARCHITECTURE%" EQU "amd64" (
>nul 2>&1 "%SYSTEMROOT%\SysWOW64\cacls.exe" "%SYSTEMROOT%\SysWOW64\config\system"
) ELSE (
>nul 2>&1 "%SYSTEMROOT%\system32\cacls.exe" "%SYSTEMROOT%\system32\config\system"
)
if '%errorlevel%' NEQ '0' (
    echo Requesting administrative privileges...
    goto UACPrompt
) else ( goto gotAdmin )
:UACPrompt
    echo Set UAC = CreateObject^("Shell.Application"^) > "%temp%\getadmin.vbs"
    set params= %*
    echo UAC.ShellExecute "cmd.exe", "/c ""%~s0"" %params:"=""%", "", "runas", 1 >> "%temp%\getadmin.vbs"
    "%temp%\getadmin.vbs"
    del "%temp%\getadmin.vbs"
    exit /B
:gotAdmin
    pushd "%CD%"
    CD /D "%~dp0"
rem ----------------------------------------------------------------------------------------------------------
setx /M path "%path%;%~dp0"
rem ----------------------------------------------------------------------------------------------------------
IF EXIST "%PROGRAMFILES(X86)%" (GOTO 64BIT) ELSE (GOTO 32BIT)
	:64BIT
		copy C:\xampp\htdocs\scsus\sqlite3\w\64\sqlite3.* C:\Windows\SysWOW64\ /Y
		copy C:\xampp\htdocs\scsus\sqlite3\w\64\sqlite3.* C:\Windows\System32\ /Y
		C:\Windows\SysWOW64\REGSVR32 "C:\Windows\SysWOW64\sqlite3.dll" 
		REGSVR32 "C:\Windows\System32\sqlite3.dll"
	GOTO END
	:32BIT
		copy C:\xampp\htdocs\scsus\sqlite3\w\32\sqlite3.* C:\Windows\System32\ /Y
		REGSVR32 "C:\Windows\System32\sqlite3.dll"
	GOTO END
:END


