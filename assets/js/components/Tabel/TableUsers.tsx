import { GridColDef } from '@mui/x-data-grid';
import styles from './datagrid.module.css';
import MainTable from './mainTable';
import { ITable, User } from '../../../types';

const Table = ({users,info}:ITable) =>
{
  let showedUsers:User[] = [];
  if(info == "toegewezen" &&  users !== undefined)
  {
    users.map((user:User) => {if((user.Location != "" && user.Location != null && user.Location != "None")){showedUsers.push(user)}})
  }
  else if(info == "noLocation" &&  users !== undefined)
  {
    users.map((user:User) => {if(user.Location == "" || user.Location == null || user.Location == "None"){showedUsers.push(user)}})
  }
    const columns: GridColDef[] = [
        { field: 'info', headerName: '', width: 75},
        {
          field: 'id',
          headerName: 'ID',
          width:100
        },
        {
          field: 'FirstName',
          headerName: 'First Name',
          width:175
        },
        {
          field: 'LastName',
          headerName: 'Last Name',
          width:175
        },
        {
          field: 'Email',
          headerName: 'Email',
          width:250
        },
        {
          field: "Position",
          editable: true,
          type: "singleSelect",
          valueOptions: ["None","Student", "Teacher", "Director"],
          width:175
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
      
        <div className={styles.tabel}>
          <MainTable data={showedUsers} columns={columns}/>
        </div>
    )
}
export default Table;