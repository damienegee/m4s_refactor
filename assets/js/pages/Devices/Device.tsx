import { useState } from 'react';
import { Box, Tab } from '@mui/material';
import { TabPanel, TabContext, TabList } from '@mui/lab';
import Table from '../../components/Tabel/TableDevices';
import { Product } from '../../../types';
import Cards from '../../components/Cards/CardDevices';
import { useViewport } from '../../hooks/viewport';
import Layout from '../../components/Layout/Layout';

let products: Product[] = [
  { id: 1, label: "DEMO01", productNumber: "DEMODEV01", serialNumber: "DEMO01", model: "DEMO01", user: { firstName: "Acht", lastName: "Leerling" }, function: "STUDENT", location: 'DEMOLOCATION' },
  { id: 2, label: "DEMO02", productNumber: "DEMODEV02", serialNumber: "DEMO02", model: "DEMO02", user: { firstName: "Twintig", lastName: "Prof" }, function: "TEACHER", location: 'DEMOLOCATION' },
  { id: 3, label: "DEMO03", productNumber: "DEMODEV03", serialNumber: "DEMO03", model: "DEMO03", user: { firstName: "Twee", lastName: "Leerling" }, function: "STUDENT", location: 'DEMOLOCATION' },
  { id: 4, label: "DEMO04", productNumber: "DEMODEV04", serialNumber: "DEMO01", model: "DEMO01", user: { firstName: "Acht", lastName: "Leerling" }, function: "STUDENT", location: '' },
  { id: 5, label: "DEMO05", productNumber: "DEMODEV05", serialNumber: "DEMO02", model: "DEMO02", user: { firstName: "Twintig", lastName: "Prof" }, function: "TEACHER", location: '' },
  { id: 6, label: "DEMO06", productNumber: "DEMODEV06", serialNumber: "DEMO03", model: "DEMO03", user: { firstName: "", lastName: "" }, function: "", location: 'DEMOLOCATION' },
  { id: 7, label: "DEMO07", productNumber: "DEMODEV07", serialNumber: "DEMO01", model: "DEMO01", user: { firstName: "Acht", lastName: "Leerling" }, function: "STUDENT", location: 'DEMOLOCATION' },
  { id: 8, label: "DEMO08", productNumber: "DEMODEV08", serialNumber: "DEMO02", model: "DEMO02", user: { firstName: "", lastName: "" }, function: "", location: 'DEMOLOCATION' },
  { id: 9, label: "DEMO09", productNumber: "DEMODEV09", serialNumber: "DEMO03", model: "DEMO03", user: { firstName: "Twee", lastName: "Leerling" }, function: "STUDENT", location: 'DEMOLOCATION' },
  { id: 10, label: "DEMO010", productNumber: "DEMODEV010", serialNumber: "DEMO01", model: "DEMO01", user: { firstName: "Acht", lastName: "Leerling" }, function: "STUDENT", location: 'DEMOLOCATION' },
  { id: 11, label: "DEMO011", productNumber: "DEMODEV011", serialNumber: "DEMO02", model: "DEMO02", user: { firstName: "Twintig", lastName: "Prof" }, function: "TEACHER", location: '' },
  { id: 12, label: "DEMO012", productNumber: "DEMODEV012", serialNumber: "DEMO03", model: "DEMO03", user: { firstName: "Twee", lastName: "Leerling" }, function: "STUDENT", location: '' },
]
const Device = () => {
  const { width } = useViewport();
  const [value, setValue] = useState('1');
  const handleChange = (event: React.SyntheticEvent, newValue: string) => {
    setValue(newValue);
  };
  return (
    <Layout title='Device'>
      <Box sx={{ width: '100%', typography: 'body1' }}>
        <TabContext value={value}>
          <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
            <TabList onChange={handleChange} aria-label="TableInfo">
              <Tab label="Toegewezen" value="1" />
              <Tab label="Toestel zonder gebruiker" value="2" />
              <Tab label="Toestellen zonder vestiging" value="3" />
            </TabList>
          </Box>
          {width > 720 ? <TabPanel value="1"><Table products={products} info='toegewezen' /></TabPanel> : products.map((product: Product) =>
            <div key={product.id}>
              <TabPanel value="1"><Cards data={product}></Cards></TabPanel>
            </div>)}
          <TabPanel value="2"><Table products={products} info='noUser' /></TabPanel>
          <TabPanel value="3"><Table products={products} info='noLocation' /></TabPanel>
          
        </TabContext>
      </Box>
    </Layout>
  )
}

export default Device;