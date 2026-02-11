@echo off
REM === CONFIGURAÇÕES ===
set DB_PATH=database.db
set SQL_SCRIPT=database.sql
set SQLITE_EXE=%~dp0DB_UTILS\sqlite3.exe

REM === VERIFICAR SE A BASE EXISTE ===
if exist "%DB_PATH%" (
    echo O ficheiro "%DB_PATH%" já existe. Eliminando...
    del /f "%DB_PATH%"
) else (
    echo Nenhuma base existente encontrada. A criar nova...
)

REM === CRIAR NOVA BASE DE DADOS COM O SCRIPT ===
echo Criando nova base de dados a partir de: "%SQL_SCRIPT%"
"%SQLITE_EXE%" "%DB_PATH%" < "%SQL_SCRIPT%"

REM === VERIFICAR RESULTADO ===
if exist "%DB_PATH%" (
    echo Base de dados criada com sucesso.
) else (
    echo ERRO: Falha ao criar base de dados!
)

REM === FINAL ===
pause
