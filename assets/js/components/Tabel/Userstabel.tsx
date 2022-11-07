import Box from '@mui/material/Box';
import { DataGrid, GridColDef } from '@mui/x-data-grid';
import CustomToolbar from '../AppBar/appbar';
import '../Tabel/Userstabel.css';

interface Users
{
    id:number
    FirstName:string,
    LastName:string,
    Email:string,
    Position:string,
    Location:string
}


const Table = () =>
{
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
    let user:Users[] = [
        {id:0,FirstName:"DEMO00",LastName:"DEMODEV00",Email:"DEMO00",Position:"Student",Location:"Demo vestiging"},
        {id:1,FirstName:"DEMO01",LastName:"DEMODEV01",Email:"DEMO01",Position:"Student",Location:"Demo vestiging"},
        {id:2,FirstName:"DEMO02",LastName:"DEMODEV02",Email:"DEMO02",Position:"Student",Location:"Demo vestiging"},
        {id:3,FirstName:"DEMO03",LastName:"DEMODEV03",Email:"DEMO03",Position:"Student",Location:"Demo vestiging"},
        {id:4,FirstName:"DEMO04",LastName:"DEMODEV04",Email:"DEMO04",Position:"Student",Location:"Demo vestiging"},
        {id:5,FirstName:"DEMO05",LastName:"DEMODEV05",Email:"DEMO05",Position:"Student",Location:"Demo vestiging"},
        {id:6,FirstName:"DEMO06",LastName:"DEMODEV06",Email:"DEMO06",Position:"Student",Location:"Demo vestiging"},
        {id:7,FirstName:"DEMO07",LastName:"DEMODEV07",Email:"DEMO07",Position:"Student",Location:"Demo vestiging"},
        {id:8,FirstName:"DEMO08",LastName:"DEMODEV08",Email:"DEMO08",Position:"Student",Location:"Demo vestiging"},
        {id:9,FirstName:"DEMO09",LastName:"DEMODEV09",Email:"DEMO09",Position:"Student",Location:"Demo vestiging"},
        {id:10,FirstName:"DEMO10",LastName:"DEMODEV10",Email:"DEMO10",Position:"Student",Location:"Demo vestiging"},
        {id:11,FirstName:"DEMO11",LastName:"DEMODEV11",Email:"DEMO11",Position:"Student",Location:"Demo vestiging"}
    ]
    return (
          <div className='tabel'>
        <Box sx={{ height: '700px', width: '100%' }} className="box">
            <DataGrid 
                components={{ Toolbar: CustomToolbar }}
                rows={user}
                columns={columns}
                rowHeight={50} {...user}
                disableSelectionOnClick
                autoPageSize={true}
                checkboxSelection
            />
        </Box>
        </div>
    )
}
export default Table;