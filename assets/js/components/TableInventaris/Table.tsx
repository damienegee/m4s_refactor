import Box from '@mui/material/Box';
import { DataGrid, GridColDef, GridValueGetterParams } from '@mui/x-data-grid';
import { useState } from 'react';

interface Product
{
    id:number
    label:string,
    productNumber:string,
    serialNumber:string,
    model?:string,
    user?:{firstName:string,lastName:string},
    function?:string,
    location?:string
}
const Table = () =>
{
    const columns: GridColDef[] = [
        { field: 'info', headerName: '', width: 100 },
        {
          field: 'label',
          headerName: 'Label',
          width: 150,
        },
        {
          field: 'productNumber',
          headerName: 'Product number',
          width: 150,
        },
        {
          field: 'serialNumber',
          headerName: 'Serial number',
          width: 150,
        },
        {
          field: 'model',
          headerName: 'Model',
          width: 150,
        },
        {
          field: 'user',
          headerName: 'User',
          width: 150,
          valueGetter: (params: GridValueGetterParams) =>
          `${params.row.user.lastName || ''} ${params.row.user.firstName.toUpperCase() || ''}`,
        },
        {
          field: 'function',
          headerName: 'Function',
          width: 150,
        },
      ];
    let products:Product[] = [
        {id:1,label:"DEMO01",productNumber:"DEMODEV01",serialNumber:"DEMO01",model:"DEMO01",user:{firstName:"Acht",lastName:"Leerling"},function:"STUDENT"},
        {id:2,label:"DEMO02",productNumber:"DEMODEV02",serialNumber:"DEMO02",model:"DEMO02",user:{firstName:"Twintig",lastName:"Prof"},function:"TEACHER"},
        {id:3,label:"DEMO03",productNumber:"DEMODEV03",serialNumber:"DEMO03",model:"DEMO03",user:{firstName:"Twee",lastName:"Leerling"},function:"STUDENT"},
    ]
    const [pageSize,setPageSize] = useState(5);
    return (
        <div>
            <Box sx={{ height: 400, width: '100%' }}>
                <DataGrid 
                    rows={products}
                    columns={columns}
                    pageSize={pageSize}
                    rowsPerPageOptions={[1,5,10,50]}
                    onPageSizeChange={(pageSize)=>setPageSize(pageSize)}
                    checkboxSelection
                    disableSelectionOnClick
                    experimentalFeatures={{ newEditingApi: true }}
                />
            </Box>
        </div>
    )
}
export default Table;