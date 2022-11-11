import Box from '@mui/material/Box';
import { DataGrid} from '@mui/x-data-grid';
import CustomToolbar from "../AppBar/Appbar"
import styles from  './datagrid.module.css';

interface Table
{
    data:any[]
    columns:any[]
}

const MainTable = ({data,columns}:Table) =>
{
    return (
        <div>
            <Box sx={{ height: '700px', width: '100%' }} className={styles.box}>
              <DataGrid 
                  components={{ Toolbar: CustomToolbar }}
                  rows={data}
                  columns={[...columns, { field: 'info', filterable: false }]}
                  rowHeight={50} {...data}
                  disableSelectionOnClick
                  autoPageSize={true}
                  checkboxSelection  
              />
          </Box>
        </div>
    )
}
export default MainTable;