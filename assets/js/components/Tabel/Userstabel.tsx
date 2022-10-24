import Box from '@mui/material/Box';
import { DataGrid, GridColDef } from '@mui/x-data-grid';
import { useState } from 'react';
import '../Tabel/Userstabel.css'

interface Users
{
    id:number
    FirstName:string,
    LastName:string,
    Email:string,
    Position:string,
    Location:string
}
const Userstabel = () =>
{
    const columns: GridColDef[] = [
        { field: 'info', headerName: '', width: 250 },
        {
          field: 'id',
          headerName: 'ID',
          width: 250,
        },
        {
          field: 'FirstName',
          headerName: 'First Name',
          width: 250,
        },
        {
          field: 'LastName',
          headerName: 'Last Name',
          width: 250,
        },
        {
          field: 'Email',
          headerName: 'Email',
          width: 275,
        },
        {
          field: 'Position',
          headerName: 'Position',
          width: 250,
        },
        {
          field: 'Location',
          headerName: 'Location',
          width: 250
        },
      ];
    let user:Users[] = [
        {id:1,FirstName:"DEMO01",LastName:"DEMODEV01",Email:"DEMO01",Position:"Student",Location:"Demo vestiging"},
        {id:2,FirstName:"DEMO02",LastName:"DEMODEV02",Email:"DEMO02",Position:"Student",Location:"Demo vestiging"},
        {id:3,FirstName:"DEMO03",LastName:"DEMODEV03",Email:"DEMO03",Position:"Student",Location:"Demo vestiging"},
        {id:4,FirstName:"DEMO04",LastName:"DEMODEV04",Email:"DEMO04",Position:"Student",Location:"Demo vestiging"},
        {id:5,FirstName:"DEMO05",LastName:"DEMODEV05",Email:"DEMO05",Position:"Student",Location:"Demo vestiging"},
        {id:6,FirstName:"DEMO06",LastName:"DEMODEV06",Email:"DEMO06",Position:"Student",Location:"Demo vestiging"}
    ]
    const [pageSize,setPageSize] = useState(5);
    return (

        <div>
        <Box sx={{ height: 400, width: '100%' }} className="box">
            <DataGrid 
                rows={user}
                columns={columns}
                pageSize={pageSize}
                rowsPerPageOptions={[5,10,50]}
                onPageSizeChange={(pageSize)=>setPageSize(pageSize)}
                checkboxSelection
                disableSelectionOnClick
                experimentalFeatures={{ newEditingApi: true }}
            />
        </Box>
        </div>
    )
}
export default Userstabel;