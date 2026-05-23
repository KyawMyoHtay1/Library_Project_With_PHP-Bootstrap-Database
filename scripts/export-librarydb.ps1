param(
    [string]$Database = "librarydb",
    [string]$User = "root",
    [string]$OutputFile = ".\backups\librarydb.local.sql",
    [string]$MysqlDumpPath = ""
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

function Resolve-MySqlDumpPath {
    param([string]$PreferredPath)

    $candidates = @(
        $PreferredPath,
        "mysqldump.exe",
        "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe",
        "C:\xampp\mysql\bin\mysqldump.exe",
        "C:\laragon\bin\mysql\mysql*\bin\mysqldump.exe"
    ) | Where-Object { $_ }

    foreach ($candidate in $candidates) {
        if ($candidate -like "*`**") {
            $match = Get-ChildItem -Path $candidate -ErrorAction SilentlyContinue | Select-Object -First 1
            if ($match) {
                return $match.FullName
            }
            continue
        }

        if ($candidate -eq "mysqldump.exe") {
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

    throw "Could not find mysqldump.exe. Pass -MysqlDumpPath explicitly."
}

$dumpExe = Resolve-MySqlDumpPath -PreferredPath $MysqlDumpPath
$outputPath = Resolve-Path (Split-Path -Parent $OutputFile) -ErrorAction SilentlyContinue

if (-not $outputPath) {
    New-Item -ItemType Directory -Path (Split-Path -Parent $OutputFile) -Force | Out-Null
}

$resolvedOutput = [System.IO.Path]::GetFullPath($OutputFile)

& $dumpExe `
    -u $User `
    $Database `
    --no-create-db `
    --skip-add-drop-database `
    --routines `
    --triggers `
    --single-transaction `
    --result-file=$resolvedOutput

Write-Host "Database export created:" $resolvedOutput
