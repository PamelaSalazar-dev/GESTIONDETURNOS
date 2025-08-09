<?php
class FuncionesAuxiliares {
    public static function limpiarPantalla() {
        echo str_repeat("\n", 50);
    }

    public static function pausar() {
        echo "Presione ENTER para continuar...";
        fgets(STDIN);
    }
}
