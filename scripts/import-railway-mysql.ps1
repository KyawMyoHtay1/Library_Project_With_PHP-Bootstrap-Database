param(
    [Parameter(Mandatory = $true)]
    [string]$DbHost,

    [Parameter(Mandatory = $true)]
    [int]$Port,

    [Parameter(Mandatory = $true)]
    [string]$User,

    [Parameter(Mandatory = $true)]
    [string]$Database,

    [string]$SqlFile = ".\backups\librarydb.local.sql",
    [string]$MysqlPath = "",
    [switch]$DryRun
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

function Resolve-MySqlPath {
    param([string]$PreferredPath)

    $candidates = @(
        $PreferredPath,
        "mysql.exe",
        "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe",
        "C:\xampp\mysql\bin\mysql.exe",
        "C:\laragon\bin\mysql\mysql*\bin\mysql.exe"
    ) | Where-Object { $_ }

    foreach ($candidate in $candidates) {
        if ($candidate -like "*`**") {
            $match = Get-ChildItem -Path $candidate -ErrorAction SilentlyContinue | Select-Object -First 1
            if ($match) {
                return $match.FullName
            }
            continue
        }

        if ($candidate -eq "mysql.exe") {
            $command = Get-Command $candidate -ErrorAction SilentlyContinue
            if ($command) {
                return $command.Source
            }
            continue
        }

        if (Test-Path $candidate) {
            return (Resolve-Path $candidate).Path
        }
    }

    throw "Could not find mysql.exe. Pass -MysqlPath explicitly."
}

$mysqlExe = Resolve-MySqlPath -PreferredPath $MysqlPath
$resolvedSqlFile = (Resolve-Path $SqlFile).Path

if (-not (Test-Path $resolvedSqlFile)) {
    throw "SQL file not found: $SqlFile"
}

$cmd = '"' + $mysqlExe + '" ' +
    '-h "' + $DbHost + '" ' +
    '-P "' + $Port + '" ' +
    '-u "' + $User + '" ' +
    '-p "' + $Database + '" < "' + $resolvedSqlFile + '"'

Write-Host "About to import:" $resolvedSqlFile
Write-Host "Host:" $DbHost
Write-Host "Port:" $Port
Write-Host "Database:" $Database
Write-Host ""
Write-Host "MySQL will prompt for the password next."

if ($DryRun) {
    Write-Host ""
    Write-Host "Dry run command:"
    Write-Host $cmd
    return
}

cmd /c $cmd
