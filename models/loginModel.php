<?php

class loginModel extends Model
{
    public function __construct() {
        parent::__construct();
    }
    
    public function getUsuario($email, $password)
    {
        $sql = "SELECT * FROM User u
                INNER JOIN Ubigeo ub
                ON(u.Ubigeo_idUbigeo=ub.idUbigeo)
                INNER JOIN Role r
                ON(u.Role_idRole=r.idRole)
                INNER JOIN UserType ut
                ON(u.UserType_idUserType=ut.idUserType)
                WHERE u.Email = '$email' AND u.Password = '$password'";

        $result=$this->_db->query($sql);
        if ($result->num_rows) {
            $datos = $result->fetch_object();

                $Department=$datos->Department;
                if ($Department=='0') {
                    $datos->ProvinceName='TODOS';
                    $datos->DistrictName='TODOS';
                    $datos->DepartmentName='TODOS';
                }else{
                    $sql2="SELECT NameUbigeo FROM Ubigeo  WHERE Department='$Department' AND Province='0'";
                    $result2 = $this->_db->query($sql2);
                    $NombreDepartamento=$result2->fetch_object();
                    $datos->DepartmentName=$NombreDepartamento->NameUbigeo;

                    $Province=$datos->Province;
                    if ($Province=='0') {
                        $datos->ProvinceName='TODOS';
                        $datos->DistrictName='TODOS';
                    }else{
                        $sql3="SELECT NameUbigeo FROM Ubigeo  WHERE Department='$Department' AND Province='$Province' AND District='0'";
                        $result3 = $this->_db->query($sql3);
                        $NombreProvincia=$result3->fetch_object();
                        $datos->ProvinceName=$NombreProvincia->NameUbigeo;

                        $District=$datos->District;
                        if ($District=='0') {
                            $datos->DistrictName='TODOS';
                        }else{
                            $sql4="SELECT NameUbigeo FROM Ubigeo  WHERE Department='$Department' AND Province='$Province' AND District='$District'";
                            $result4 = $this->_db->query($sql4);
                            $NombreDistrito=$result4->fetch_object();
                            $datos->DistrictName=$NombreDistrito->NameUbigeo;
                        }
                    }
                }

        }
        else{
            $datos = false;
        }

        return $datos;
    }

    // $sql = "SELECT idUser, Name, LastName, State, NameUbigeo, idUsertype, DetailRole FROM User u
    //             INNER JOIN Ubigeo ub
    //             ON(u.Ubigeo_idUbigeo=ub.idUbigeo)
    //             INNER JOIN Role r
    //             ON(u.Role_idRole=r.idRole)
    //             INNER JOIN UserType ut
    //             ON(u.UserType_idUserType=ut.idUserType)
    //             WHERE u.Email = '$email' AND u.Password = '$password'";
}
?>