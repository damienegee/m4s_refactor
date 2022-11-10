import { useState } from 'react';
import { Box, Tab } from '@mui/material';
import { TabPanel, TabContext, TabList } from '@mui/lab';
import styles from "../page.module.css"
import AddRoundedIcon from '@mui/icons-material/AddRounded';
import Btn from '../../components/Button/Btn';
import Table from '../../components/Tabel/Userstabel';
import Sidebar from '../../components/Sidebar/Sidebar';
import Header from '../../components/Header/Header';


const Users = () => {
  const [value, setValue] = useState('1');
  const handleChange = (event: React.SyntheticEvent, newValue: string) => {
    setValue(newValue);
  };
  return (
    <div className={styles.container}>
      <Sidebar />
      <div className={styles.pageContainer}>
        <Header title="Users" />
        <Box sx={{ width: '100%', typography: 'body1' }}>
          <TabContext value={value}>
            <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
              <TabList onChange={handleChange} aria-label="lab API tabs example">
                <Tab label="Toegewezen" value="1" />
                <Tab label="Toestellen zonder vestiging" value="2" />
              </TabList>
            </Box>
            <Btn text="Voeg gebruiker toe" size={15} Icon={<AddRoundedIcon />} ></Btn>
            <TabPanel value="1"><Table /></TabPanel>
            <TabPanel value="2"><Table /></TabPanel>
          </TabContext>
        </Box>
      </div>
    </div>
  )
}

export default Users;