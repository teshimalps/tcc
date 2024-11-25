<?php
//toda classe php tem a inicial em maisculo
class Bnc
{
    //declaração de variaveis privadas
    private static $dbname = "db";
    private static $host = "localhost";
    private static $user = "root";
    private static $pass = "";

    private static $con = null;

    //construção da função principal
    public function __construct()
    {
        //ao dar falha no construtor exibe essa mensagm
        die("A função construtora não pode ser inicializada!");
    }

    //construção da função que conecta ao banco de dados
    public static function conectar()
    {
        //verifica se a conexão está nula, se estiver conecta ao banco
        if (self::$con == null) {
            //tratamento de erros referente a conexao ou sql
            try {
                self::$con = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname, self::$user, self::$pass);
            } catch (Exception $erro) {
                die($erro->getMessage());
            }
        }
        return self::$con;
    }

    //construção da função que desconecta do banco de dados
    public static function desconectar()
    {
        self::$con = null;
    }
}
