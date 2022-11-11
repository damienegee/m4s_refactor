import {GridColDef } from '@mui/x-data-grid';
import MainTable from './mainTable';

interface User
{
    id:number
    FirstName:string,
    LastName:string,
    Email:string,
    Position:string,
    Location:string
}
interface Table
{
  users:User[]
  info:string
}

const Table = ({users,info}:Table) =>
{
  let showedUsers:User[] = [];
  if(info == "toegewezen")
  {
    users.map((user:User) => {if((user.Location != "" && user.Location != null && user.Location != "None")){showedUsers.push(user)}})
  }
  else if(info == "noLocation")
  {
    users.map((user:User) => {if(user.Location == "" || user.Location == null || user.Location == "None"){showedUsers.push(user)}})
  }

    const columns: GridColDef[] = [
        { field: 'info', headerName: '', width: 75 },
        {
          field: 'id',
          headerName: 'ID',
          width: 150,
        },
        {
          field: 'FirstName',
          headerName: 'First Name',
          width: 260,
        },
        {
          field: 'LastName',
          headerName: 'Last Name',
          width: 260,
        },
        {
          field: 'Email',
          headerName: 'Email',
          width: 350,
        },
        {
          field: "Position",
          editable: true,
          type: "singleSelect",
          valueOptions: ["None","Student", "Teacher", "Director"],
          width:250
        },
        {
          field: "Location",
          editable: true,
          type: "singleSelect",
          valueOptions: ["None","Ap","KDG", "UA", "KSV"],
          width:250
        },
      ];
    return (
        <div className='tabel'>
          <MainTable data={showedUsers} columns={columns}/>
        </div>
    )
}
export default Table;