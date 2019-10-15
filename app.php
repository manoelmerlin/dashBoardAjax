<?php

    class Dashboard {
        public $dataInicio;
        public $dataFim;
        public $numeroDeVendas;
        public $totalDeVendas;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
            return $this;
        }
    }

    class Conexao {
        private $host = 'localhost';
        private $dbName = 'dashboard';
        private $user = 'root';
        private $password = '';


        public function conectar() {
            try {
                $conexao = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbName",
                    "$this->user",
                    "$this->password"
                );

                $conexao->exec('set charset set utf8');

                return $conexao;

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

    }

    class Bd {
        private $conexao;
        private $dashboard;

        public function __construct(Conexao $conexao, Dashboard $dashboard) {
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        public function getNumeroVendas() {
            $query = "SELECT count(*) as numero_vendas FROM tb_vendas where data_venda BETWEEN :dataInicio and :dataFim";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':dataInicio', $this->dashboard->__get('dataInicio'));
            $stmt->bindValue(':dataFim', $this->dashboard->__get('dataFim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;

        }

        public function getTotalVendas() {
            $query = "SELECT SUM(total) as total_vendas FROM tb_vendas where data_venda BETWEEN :dataInicio and :dataFim";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':dataInicio', $this->dashboard->__get('dataInicio'));
            $stmt->bindValue(':dataFim', $this->dashboard->__get('dataFim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;

        }

    }

    $dashboard = new Dashboard();
    $conexao = new Conexao();
    $bd = new Bd($conexao, $dashboard);

    $competencia = $_GET['competencia'];
    $dashboard->__set('dataInicio', $competencia . '-01');
    $dashboard->__set('dataFim', $competencia . '-31');
    $dashboard->__set('totalDeVendas', $bd->getTotalVendas());
    $dashboard->__set('numeroDeVendas', $bd->getNumeroVendas());

    

    echo json_encode($dashboard); 
    //print_r($competencia);