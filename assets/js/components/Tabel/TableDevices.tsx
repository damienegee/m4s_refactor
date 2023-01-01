import { GridColDef, GridValueGetterParams } from '@mui/x-data-grid';
import styles from './datagrid.module.css';
import MainTable from './mainTable';
import { Product,ITable } from '../../../types';

const Table = ({products,info}:ITable) =>
{
  let showedProducts:Product[] = [];
  if(info == "toegewezen" && products !== undefined)
  {
    products.map((product:Product) => {if((product.location != "" && product.location != null) && (product.user?.firstName != null && product.user.firstName != "")){showedProducts.push(product)}})
  }
  else if(info == "noLocation" && products !== undefined)
  {
    products.map((product:Product) => {if(product.location == "" || product.location == null){showedProducts.push(product)}})
  }
  else if(info == "noUser" && products !== undefined)
  {
    products.map((product:Product) => {if(product.user?.firstName == "" || product.user?.firstName == null){showedProducts.push(product)}})
  }
    const columns: GridColDef[] = [
        { field: 'info', headerName: '', width: 100 },
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
          field: 'location',
          headerName: 'Location',
          width: 150,
        },
      ];
    return (
      <div className={styles.tabel}>
          <MainTable data={showedProducts} columns={columns}/>
      </div>
  )
}
export default Table;