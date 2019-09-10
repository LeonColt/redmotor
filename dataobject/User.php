<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 06/04/2017
 * Time: 12:49
 */
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'core/DataBaseObject.php';
class User extends DataBaseObject implements Serializable
{
    const NULL = 0;
    const TNAME = "red_motor_user";

    const C_ID = "id";
    const C_USERNAME = "username";
    const C_PASS = "pass";
    const C_NAME = "nama";
    const C_NO_KTP = "no_ktp";
    const C_BIRTH_DATE = "tanggal_lahir";
    const C_SEX = "jenis_kelamin";
    const C_ADDRESS = "alamat";
    const C_TELEPHONE = "telepon";
    const C_EMAIL = "email";
    const C_LEVEL = "level_user";

    const SEX_FEMALE = 0;
    const SEX_MALE = 1;
    const SEX_OTHER = 2;

    const LEVEL_SUPER_ADMIN = 1;
    const LEVEL_ADMIN = 2;
    const LEVEL_CUSTOMER = 3;
    CONST LEVEL_CUSTOMER_SERVICE = 4;
    private $id, $username, $name, $password, $birth_date, $sex = User::SEX_FEMALE, $address, $telp, $email, $ktp, $password_hashed, $level = User::LEVEL_CUSTOMER;
    /**
     * User constructor.
     * @param int $id
     */
    public function __construct($id = null){$this->id = $id;}
    public function getId() : int{return $this->id;}
    public function getUsername() : string{return $this->username;}
    public function setUsername(string $username){$this->username = $username;}
    public function getName() : string{return $this->name;}
    public function setName(string $name){$this->name = $name;}
    public function getPassword() : string{return $this->password;}
    public function setPassword(string $password){$this->password = $password;$this->password_hashed = password_hash($this->password, PASSWORD_DEFAULT);}
    public function setHashedPassword(string $hashed_password) {$this->password_hashed = $hashed_password;}
    public function getHashedPassword() : string {return $this->password_hashed;}
    public function getBirthDate() : string {return $this->birth_date;}
    public function setBirthDate(string $birth_date){$this->birth_date = $birth_date;}
    public function setSex(int $sex){$this->sex = $sex;}
    public function getSex() : int {return $this->sex;}
    public function asFemale() {$this->sex = User::SEX_FEMALE;}
    public function asMale() {$this->sex = User::SEX_MALE;}
    public function asSexOther(){$this->sex = User::SEX_OTHER;}
    public function isFemale() : bool {return $this->sex === User::SEX_FEMALE;}
    public function isMale() : bool {return $this->sex === User::SEX_MALE;}
    public function getAddress() : string{return $this->address;}
    public function setAddress(string $address){$this->address = $address;}
    public function getTelp() : string{return $this->telp;}
    public function setTelp(string $telp){$this->telp = $telp;}
    public function getEmail(){return $this->email;}
    public function setEmail(string $email){$this->email = $email;}
    public function getKtp() : string {return $this->ktp;}
    public function setKtp(string $ktp){$this->ktp = $ktp;}
    public function setLevel(int $level){$this->level = $level;}
    public function getLevel() : int {return $this->level;}
    public function asSuperAdmin(){$this->level = User::LEVEL_SUPER_ADMIN;}
    public function asAdmin(){$this->level = User::LEVEL_ADMIN;}
    public function asCustomer(){$this->level = User::LEVEL_CUSTOMER;}
    public function isSuperAdmin() : bool {return $this->level === User::LEVEL_SUPER_ADMIN;}
    public function isAdmin() : bool {return $this->level === User::LEVEL_ADMIN;}
    public function isCustomer() : bool {return $this->level === User::LEVEL_CUSTOMER;}
    /**
     * @return bool
     * @throws Exception
     */
    public function login() : bool {
        if(!$this->load()) throw new Exception("User tidak ditemukan");
        return (password_verify($this->password, $this->password_hashed));
    }
    public function changePassword() {
        if(!$this->isRunnerValid()) throw new Exception("Invalid Connection to Database");
        $update = new Update(User::TNAME);
        $update->appendSet(new Column(User::C_PASS), '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->getHashedPassword()));
        $update->append_where(User::C_ID." = ?");
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->getId()));
        $update->append_where(User::C_USERNAME." = ?", 'OR');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->getUsername()));
        $query_array = new QueryArray();
        $query_array->append($update);
        $this->getRunner()->appendQueryArray($query_array);
        $this->getRunner()->execute();
        return $update->isSuccessExecuted();
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current() : User
    {
        // TODO: Implement current() method.
        $user = new User($this->getArr()[User::C_ID]);
        $user->setUsername($this->getArr()[User::C_USERNAME]);
        $user->setName($this->getArr()[User::C_NAME]);
        $user->setKtp($this->getArr()[User::C_NO_KTP]);
        $user->setBirthDate($this->getArr()[User::C_BIRTH_DATE]);
        ( (int)$this->getArr()[User::C_SEX] === User::SEX_FEMALE ) ? $user->asFemale() : (((int)$this->getArr()[User::C_SEX] === User::SEX_MALE) ? $user->asMale() : $user->asSexOther());
        $user->setAddress($this->getArr()[User::C_ADDRESS]);
        $user->setTelp($this->getArr()[User::C_TELEPHONE]);
        $user->setEmail($this->getArr()[User::C_EMAIL]);
        switch((int)$this->getArr()[User::C_LEVEL]) {
            case User::LEVEL_SUPER_ADMIN: $user->asSuperAdmin(); break;
            case User::LEVEL_ADMIN: $user->asAdmin(); break;
            case User::LEVEL_CUSTOMER: $user->asCustomer(); break;
            default: $this->asCustomer();
        }
        return $user;
    }

    protected function onLoad() : Select
    {
        // TODO: Implement onLoad() method.
        $select = new Select(User::TNAME);
        $select->appendColumn(new Column(User::C_ID));
        $select->appendColumn(new Column(User::C_USERNAME));
        $select->appendColumn(new Column(User::C_PASS));
        $select->appendColumn(new Column(User::C_NAME));
        $select->appendColumn(new Column(User::C_NO_KTP));
        $select->appendColumn(new Column(User::C_BIRTH_DATE));
        $select->appendColumn(new Column(User::C_SEX));
        $select->appendColumn(new Column(User::C_ADDRESS));
        $select->appendColumn(new Column(User::C_TELEPHONE));
        $select->appendColumn(new Column(User::C_EMAIL));
        $select->appendColumn(new Column(User::C_LEVEL));
        $select->append_where(User::C_ID.' = ?');
        $select->append_where(User::C_USERNAME.' = ?', 'OR');
        $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->id));
        $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $this->username));
        $select->fetchAssoc();
        return $select;
    }

    protected function onAdd() : Insert
    {
        // TODO: Implement onAdd() method.
        if($this->level === 0)
            throw new Exception("Cannot Register User with no Privilege");
        if(empty($this->username))
            throw new Exception("Username Cannot Be Empty");
        $random = new Random();
        do {
            $exists = false;
            $id = $random->random_number_int(0);
            $select = new Select(User::TNAME);
            $select->appendColumn(new Column(User::C_ID));
            $select->append_where(User::C_ID.' = ?');
            $select->appendParameter(new Parameter($select->getParameterVariableIntegerOrder(), $id));
            $this->getRunner()->clearQueryArrayArray();
            $query_array = new QueryArray();
            $query_array->append($select);
            $this->getRunner()->appendQueryArray($query_array);
            $this->getRunner()->execute();
            if($select->getStatement()->rowCount() > 0) $exists = true;
        } while($exists);
        $this->id = $id;;
        $insert = new Insert(User::TNAME);
        $insert->appendColumnValue(new Column(User::C_ID), new Parameter($insert->getParameterVariableIntegerOrder(), $this->id));
        $insert->appendColumnValue(new Column(User::C_USERNAME), new Parameter($insert->getParameterVariableIntegerOrder(), $this->username));
        $insert->appendColumnValue(new Column(User::C_PASS), new Parameter($insert->getParameterVariableIntegerOrder(), $this->password_hashed));
        $insert->appendColumnValue(new Column(User::C_NAME), new Parameter($insert->getParameterVariableIntegerOrder(), $this->name));
        $insert->appendColumnValue(new Column(User::C_NO_KTP), new Parameter($insert->getParameterVariableIntegerOrder(), $this->ktp));
        $insert->appendColumnValue(new Column(User::C_BIRTH_DATE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->birth_date));
        $insert->appendColumnValue(new Column(User::C_SEX), new Parameter($insert->getParameterVariableIntegerOrder(), $this->sex));
        $insert->appendColumnValue(new Column(User::C_ADDRESS), new Parameter($insert->getParameterVariableIntegerOrder(), $this->address));
        $insert->appendColumnValue(new Column(User::C_TELEPHONE), new Parameter($insert->getParameterVariableIntegerOrder(), $this->telp));
        $insert->appendColumnValue(new Column(User::C_EMAIL), new Parameter($insert->getParameterVariableIntegerOrder(), $this->email));
        $insert->appendColumnValue(new Column(User::C_LEVEL), new Parameter($insert->getParameterVariableIntegerOrder(), $this->level));
        return $insert;
    }

    protected function onUpdate() : Update
    {
        // TODO: Implement onUpdate() method.
        if(empty($this->id))
            throw new Exception("Id cannot be empty when update");
        $update = new Update(User::TNAME);
        $update->appendSet(new Column(User::C_NAME), '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->name));
        $update->appendSet(new Column(User::C_NO_KTP), '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->ktp));
        $update->appendSet(new Column(User::C_BIRTH_DATE), '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->birth_date));
        $update->appendSet(new Column(User::C_SEX), '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->sex));
        $update->appendSet(new Column(User::C_ADDRESS), '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->address));
        $update->appendSet(new Column(User::C_TELEPHONE), '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->telp));
        $update->appendSet(new Column(User::C_EMAIL), '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->email));
        $update->appendSet(new Column(User::C_LEVEL), '?');
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->level));
        $update->append_where(new Column(User::C_ID)."= ?");
        $update->appendParameter(new Parameter($update->getParameterVariableIntegerOrder(), $this->id));
        return $update;
    }

    protected function onRewind(): Select
    {
        // TODO: Implement onRewind() method.
        $select = new Select(User::TNAME);
        $select->appendColumn(new Column(User::C_ID));
        $select->appendColumn(new Column(User::C_USERNAME));
        $select->appendColumn(new Column(User::C_PASS));
        $select->appendColumn(new Column(User::C_NAME));
        $select->appendColumn(new Column(User::C_NO_KTP));
        $select->appendColumn(new Column(User::C_BIRTH_DATE));
        $select->appendColumn(new Column(User::C_SEX));
        $select->appendColumn(new Column(User::C_ADDRESS));
        $select->appendColumn(new Column(User::C_TELEPHONE));
        $select->appendColumn(new Column(User::C_EMAIL));
        $select->appendColumn(new Column(User::C_LEVEL));
        $select->fetchAssoc();
        return $select;
    }

    public function onDelete() : Delete
    {
        // TODO: Implement onDelete() method.
        $delete = new Delete(User::TNAME);
        $delete->append_where(User::C_ID.' = ?');
        $delete->appendParameter(new Parameter($delete->getParameterVariableIntegerOrder(), $this->id));
        $delete->append_where(User::C_USERNAME.' = ?', 'OR');
        $delete->appendParameter(new Parameter($delete->getParameterVariableIntegerOrder(), $this->username));
        return $delete;
    }

    protected function onPostLoad($data)
    {
        // TODO: Implement onPostLoad() method.
        if($data === null) throw new Exception("User Not Found");
        $this->id = (int)$data[User::C_ID];
        $this->username = $data[User::C_USERNAME];
        $this->password_hashed = $data[User::C_PASS];
        $this->name = $data[User::C_NAME];
        $this->birth_date = $data[User::C_BIRTH_DATE];
        ( (int)$data[User::C_SEX] === User::SEX_FEMALE ) ? $this->asFemale() : (((int)$data[User::C_SEX] === User::SEX_MALE) ? $this->asMale() : $this->asSexOther());
        $this->address = $data[User::C_ADDRESS];
        $this->ktp = $data[User::C_NO_KTP];
        $this->telp = $data[User::C_TELEPHONE];
        $this->email = $data[User::C_EMAIL];
        switch((int)$data[User::C_LEVEL]) {
            case User::LEVEL_SUPER_ADMIN: $this->asSuperAdmin(); break;
            case User::LEVEL_ADMIN: $this->asAdmin(); break;
            case User::LEVEL_CUSTOMER: $this->asCustomer(); break;
            default: $this->asCustomer();
        }
    }
    public function serialize()
    {
        $temp = array();
        $temp[User::C_ID] = $this->id;
        $temp[User::C_USERNAME] = $this->username;
        $temp[User::C_NAME] = $this->name;
        $temp[User::C_NO_KTP] = $this->ktp;
        $temp[User::C_BIRTH_DATE] = $this->birth_date;
        $temp[User::C_SEX] = $this->sex;
        $temp[User::C_ADDRESS] = $this->address;
        $temp[User::C_TELEPHONE] = $this->telp;
        $temp[User::C_EMAIL] = $this->email;
        $temp[User::C_LEVEL] = $this->level;
        return json_encode($temp);
    }
    public function unserialize($serialized)
    {
        $temp = json_decode($serialized, true);
        $this->id = $temp[User::C_ID];
        $this->username = $temp[User::C_USERNAME];
        $this->name = $temp[User::C_NAME];
        $this->ktp = $temp[User::C_NO_KTP];
        $this->birth_date = $temp[User::C_BIRTH_DATE];
        $this->sex = $temp[User::C_SEX];
        $this->address = $temp[User::C_ADDRESS];
        $this->telp = $temp[User::C_TELEPHONE];
        $this->email = $temp[User::C_EMAIL];
        $this->level = $temp[User::C_LEVEL];
    }
}