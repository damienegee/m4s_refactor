import Box from '@mui/material/Box';
import { DataGrid, GridColDef, GridValueGetterParams } from '@mui/x-data-grid';
import { useState } from 'react';
import CustomToolbar from '../AppBar/appbar';
import '../Tabel/datagrid.css';

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
interface Table
{
  products:Product[]
  info:string
}
const Table = ({products,info}:Table) =>
{
  let showedProducts:Product[] = [];
  if(info == "toegewezen")
  {
    products.map((product:Product) => {if((product.location != "" && product.location != null) && (product.user?.firstName != null && product.user.firstName != "")){showedProducts.push(product)}})
  }
  else if(info == "noLocation")
  {
    products.map((product:Product) => {if(product.location == "" || product.location == null){showedProducts.push(product)}})
  }
  else if(info == "noUser")
  {
    products.map((product:Product) => {if(product.user?.firstName == "" || product.user?.firstName == null){showedProducts.push(product)}})
  }
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
        {
          field: 'location',
          headerName: 'Location',
          width: 150,
        },
      ];
    const [pageSize,setPageSize] = useState(5);
    return (
      <div>
        <div className='tabel'>
          <Box sx={{ height: 400, width: '100%' }} className="box">
              <DataGrid 
                  components={{ Toolbar: CustomToolbar }}
                  rows={showedProducts}
                  columns={columns}
                  rowHeight={50} {...showedProducts}
                  checkboxSelection
                  disableSelectionOnClick
                  pageSize={pageSize}
                  rowsPerPageOptions={[1,5,10,50]}
                  onPageSizeChange={(pageSize)=>setPageSize(pageSize)}
              />
          </Box>
        </div>
      </div>
  )
}
export default Table;