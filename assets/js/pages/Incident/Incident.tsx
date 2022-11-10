import { useState } from 'react';
import styles from  '../page.module.css'
import { Box,Tab } from '@mui/material';
import {TabPanel,TabContext,TabList} from '@mui/lab';
import Header from "../../components/Header/Header";
import Sidebar from "../../components/Sidebar/Sidebar";
import Table from '../../components/Tabel/datagridIncident';
import Btn from '../../components/Button/Btn';
import { Link } from 'react-router-dom';
import AddRoundedIcon from '@mui/icons-material/AddRounded';


const Incident = () => {
  const [value, setValue] = useState('1');
  const handleChange = (event: React.SyntheticEvent, newValue: string) => {
    setValue(newValue);
  };
  return (
    <div className={styles.container}>
        <Sidebar/>
        <div className={styles.pageContainer}>
            <Header title="Indicents"/>
            <Box sx={{ width: '100%', typography: 'body1' }}>
              <TabContext value={value}>
                <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>

                  <TabList onChange={handleChange} aria-label="lab API tabs example">
                    <Tab label="Open" value="1" />
                    <Tab label="Awaiting approval" value="2" />
                    <Tab label="Closed" value="3" />
                    <Tab label="Graph" value="4" />
                  </TabList>
                </Box>
                
                <Btn text="Report incident" size={15} Icon={<AddRoundedIcon/>} ></Btn>
                <TabPanel value="1"><Table/></TabPanel>
                <TabPanel value="2"><Table/></TabPanel>
              </TabContext>
            </Box>  
        </div>
    </div>
  )
}

export default Incident;