import {GridColDef } from '@mui/x-data-grid';
import MainTable from './mainTable';
import styles from './datagrid.module.css'
import './styles.css'
 
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
        { field: 'info', headerName: 'Test', width: 25 },
        {
          field: 'id',
          headerName: 'ID'
        },
        {
          field: 'FirstName',
          headerName: 'First Name',
          maxWidth:100,
          headerClassName: `${styles.positie}`
        },
        {
          field: 'LastName',
          headerName: 'Last Name',
          headerClassName: `${styles.positie}`
        },
        {
          field: 'Email',
          headerName: 'Email',
          headerClassName: `${styles.positie}`,
          maxWidth:100
        },
        {
          field: "Position",
          editable: true,
          type: "singleSelect",
          valueOptions: ["None","Student", "Teacher", "Director"]
        },
        {
          field: "Location",
          editable: true,
          type: "singleSelect",
          valueOptions: ["None","Ap","KDG", "UA", "KSV"]
        },
      ];
    return (
        <div className='tabel'>
          <MainTable data={showedUsers} columns={columns}/>
        </div>
    )
}
export default Table;