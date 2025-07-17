<?php
class FuncionesAuxiliares {
    public static function limpiarPantalla() {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cls');
        } else {
            system('clear');
        }
    }

    public static function pausar() {
        echo "Presione ENTER para continuar...";
        fgets(STDIN);
    }
}
