import { Checkbox } from '@mui/material';
import Box from '@mui/material/Box';
import { DataGrid, GridColDef } from '@mui/x-data-grid';
import { Users } from '../../../types';
import CustomToolbar from '../AppBar/AppBar';
import styles from './datagrid.module.css';
import MainTable from './mainTable';

let user:Users[] = [
  {id:0,FirstName:"DEMO00",LastName:"DEMODEV00",Email:"youssef.j@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:1,FirstName:"DEMO01",LastName:"DEMODEV01",Email:"ri.j@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:2,FirstName:"DEMO02",LastName:"DEMODEV02",Email:"zaef.j@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:3,FirstName:"DEMO03",LastName:"DEMODEV03",Email:"jaeaz.p@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:4,FirstName:"DEMO04",LastName:"DEMODEV04",Email:"hdehbez@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:5,FirstName:"DEMO05",LastName:"DEMODEV05",Email:"podsq.r@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:6,FirstName:"DEMO06",LastName:"DEMODEV06",Email:"bdeehgezd@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:7,FirstName:"DEMO07",LastName:"DEMODEV07",Email:"feziuhfe.ojhds@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:8,FirstName:"DEMO08",LastName:"DEMODEV08",Email:"uehuhdgs@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:9,FirstName:"DEMO09",LastName:"DEMODEV09",Email:"cshjchjvdc@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:10,FirstName:"DEMO10",LastName:"DEMODEV10",Email:"vkjbdfhjgdfuif@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:11,FirstName:"DEMO11",LastName:"DEMODEV11",Email:"cdhbhudcvsyucv@hotmail.com",Position:"Student",Location:"Demo vestiging"}
]
const Table = () =>
{
  
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
          <MainTable data={user} columns={columns}/>
        </div>
    )
}
export default Table;