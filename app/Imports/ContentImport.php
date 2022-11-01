<?PHP
namespace App\Imports;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;

class ContentImport implements ToModel
{
/**
* @param array $row
*
* @return User|null
*/
public function model(array $row)
{
//dd($row);//写入数据库使用
}
}
