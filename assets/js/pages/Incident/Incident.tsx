import { useState } from 'react';
import { Autocomplete, Box, Button, FormControl, FormControlLabel, Modal, Radio, RadioGroup, TextField } from '@mui/material';
import { TabPanel, TabContext, TabList } from '@mui/lab';
import styles from "./IncidentForm.module.css"
import { style } from '@mui/system';
import { useViewport } from '../../hooks/viewport'
import Table from '../../components/Tabel/TableIncidents';
import { Incidents } from '../../../types';
import CardsIncident from '../../components/Cards/CardsIncident';
import Layout from '../../components/Layout/Layout';
import StyledTab from '../../components/StyledTab/StyledTab';

const Incident = () => {
  const [value, setValue] = useState('1');
  const [open, setOpen] = useState(false);
  const [valueForm, setValueForm] = useState('');
  const [serialNumber, setSeriaNumber] = useState("")
  const [generalSelect, setGeneralSelect] = useState("")
  const [school, setSchool] = useState("")
  const [openModal, setOpenModal] = useState(false);

  let incidents: Incidents[] = [
    { id: 0, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },
    { id: 1, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },
    { id: 2, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },
    { id: 3, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },
    { id: 4, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },
    { id: 5, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },
    { id: 6, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },
    { id: 7, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },
    { id: 8, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },
    { id: 9, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },
    { id: 10, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },
    { id: 11, Student: "DEMO00", SerialNumber: "DEMODEV00", Problem: "DEMO00", Status: "Rejected", Created: "Demo vestiging" },


  ]

  const handleChange = (event: React.SyntheticEvent, newValue: string) => {
    setValue(newValue);
  };

  const handleOpen = () => {
    setOpenModal(true);
  };
  const handleClose = () => {
    setOpenModal(false);
  };

  const handleChangeForm = (event: React.ChangeEvent<HTMLInputElement>) => {
    setValueForm((event.target as HTMLInputElement).value);
  };

  const handleSerialNumberChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setSeriaNumber((event.target as HTMLInputElement).value);
  };
  const generalIncidents = [

    { label: 'Defective cover' },
    { label: 'Docking issue' },
    { label: 'DVD drive broken' },
  ]

  const batteryAdapter = [
    { label: 'Adapter damaged' },
    { label: 'Lost adapter' },
    { label: 'Does not charge' },
  ]

  const screen = [
    { label: 'External view does not work' },
    { label: 'No image' },
    { label: 'Line in display' },
  ]

  const schools = [
    { label: 'School' },
    { label: 'At home with the customer' },
    { label: 'Service point Kortrijk' },
  ]

  const { width } = useViewport();
  if (width > 720) {
    return (
      <Layout title='Incidents'>
        <Box sx={{ width: '100%', typography: 'body1' }}>
          <TabContext value={value}>
            <Box sx={{ borderColor: 'divider' }}>

              <TabList onChange={handleChange} aria-label="lab API tabs example">
                <StyledTab label="Open" value="1" />
                <StyledTab label="Awaiting approval" value="2" />
                <StyledTab label="Closed" value="3" />
                <StyledTab label="Graph" value="4" />
              </TabList>
            </Box>

            <Button onClick={handleOpen}>Report incident</Button>
            {openModal &&

              <Modal
                sx={{ position: 'absolute', top: '10%', width: "100%", overflowY: 'scroll' }}
                open={openModal}
                onClose={handleClose}
                aria-labelledby="parent-modal-title"
                aria-describedby="parent-modal-description"
              >
                <Box sx={{ ...style, width: 400 }} className={styles.box} >
                  <FormControl>
                    <h1>Report a new defect</h1>
                    <h2>Device</h2>
                    <Autocomplete
                      disablePortal
                      id="combo-box-demo"
                      options={generalIncidents}
                      sx={{ width: 300 }}
                      renderInput={(params) => <TextField sx={{ margin: 1 }} {...params} label="General" />}
                    />
                    <Autocomplete
                      disablePortal
                      id="combo-box-demo"
                      options={batteryAdapter}
                      sx={{ width: 300 }}
                      renderInput={(params) => <TextField sx={{ margin: 1 }} {...params} label="Battery/Adapter" />}
                    />
                    <Autocomplete
                      disablePortal
                      id="combo-box-demo"
                      options={screen}
                      sx={{ width: 300 }}
                      renderInput={(params) => <TextField sx={{ margin: 1 }} {...params} label="Screen" />}
                    />



                    <TextField sx={{ margin: 1 }}
                      fullWidth
                      id="outlined-name"
                      label="Serial number"
                      value={serialNumber}
                      onChange={handleSerialNumberChange}
                    />
                    <RadioGroup sx={{ margin: 1 }}
                      aria-labelledby="demo-controlled-radio-buttons-group"
                      name="controlled-radio-buttons-group"
                      value={valueForm}
                      onChange={handleChangeForm}
                    >
                      <FormControlLabel value="nietBruikbaar" control={<Radio />} label="Toestel is niet meer bruikbaar" />
                      <FormControlLabel value="nogBruikbaar" control={<Radio />} label="Toestel is nog bruikbaar" />
                      <FormControlLabel value="nogInGebruik" control={<Radio />} label="Toestel nog in gebruik" />
                    </RadioGroup>
                    <TextField sx={{ margin: 1 }} label="Problem description" color="secondary" variant='outlined' />

                    <h2>School info</h2>
                    <TextField sx={{ margin: 1 }} id="outlined-basic" label="School name" variant="outlined" />

                    <Autocomplete
                      disablePortal
                      id="combo-box-demo"
                      options={schools}
                      sx={{ width: 300 }}
                      renderInput={(params) => <TextField sx={{ margin: 1 }} {...params} label="Repair location" />}
                    />

                    <h2>Contact details</h2>
                    <TextField sx={{ margin: 1 }} label="First name" color="primary" variant='outlined' /><TextField sx={{ margin: 1 }} label="Surname" color="primary" variant='outlined' />
                    <Button sx={{ margin: 1, backgroundColor: "black" }} variant="contained">Submit</Button>
                  </FormControl>

                </Box>
              </Modal>}
            <TabPanel value="1"><Table incidents={incidents} info="Incidenten" /></TabPanel>
            <TabPanel value="2"><Table incidents={incidents} info="Incidenten" /></TabPanel>
            <TabPanel value="3"><h1>Coming soon</h1></TabPanel>
            <TabPanel value="4"><h1>Coming soon</h1></TabPanel>
          </TabContext>
        </Box>

      </Layout>
    )
  }
  else {
    return (
      <Layout title='Incidents'>
        <Box sx={{ width: '100%', typography: 'body1' }}>
          <TabContext value={value}>
            <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
              <TabList onChange={handleChange} aria-label="lab API tabs example">
                <StyledTab label="Open" value="1" />
                <StyledTab label="Awaiting approval" value="2" />
                <StyledTab label="Closed" value="3" />
                <StyledTab label="Graph" value="4" />
              </TabList>
            </Box>
            <Button onClick={handleOpen}>Report incident</Button>
            {openModal &&

              <Modal
                open={openModal}
                onClose={handleClose}
                aria-labelledby="parent-modal-title"
                aria-describedby="parent-modal-description"
                sx={{ position: 'flex', top: '5%', overflow: 'scroll' }}
              >
                <Box sx={{ ...style, width: 400 }} className={styles.box}>
                  <FormControl>
                    <h1>Report a new defect</h1>
                    <h2>Device</h2>
                    <Autocomplete
                      disablePortal
                      id="combo-box-demo"
                      options={generalIncidents}
                      sx={{ width: 200 }}
                      renderInput={(params) => <TextField sx={{ margin: 1 }} {...params} label="General" />}
                    />
                    <Autocomplete
                      disablePortal
                      id="combo-box-demo"
                      options={batteryAdapter}
                      sx={{ width: 300 }}
                      renderInput={(params) => <TextField sx={{ margin: 1 }} {...params} label="Battery/Adapter" />}
                    />
                    <Autocomplete
                      disablePortal
                      id="combo-box-demo"
                      options={screen}
                      sx={{ width: 300 }}
                      renderInput={(params) => <TextField sx={{ margin: 1 }} {...params} label="Screen" />}
                    />

                    <TextField sx={{ margin: 1 }}
                      fullWidth
                      id="outlined-name"
                      label="Serial number"
                      value={serialNumber}
                      onChange={handleSerialNumberChange}
                    />
                    <RadioGroup sx={{ margin: 1 }}
                      aria-labelledby="demo-controlled-radio-buttons-group"
                      name="controlled-radio-buttons-group"
                      value={valueForm}
                      onChange={handleChangeForm}
                    >
                      <FormControlLabel value="nietBruikbaar" control={<Radio />} label="Toestel is niet meer bruikbaar" />
                      <FormControlLabel value="nogBruikbaar" control={<Radio />} label="Toestel is nog bruikbaar" />
                      <FormControlLabel value="nogInGebruik" control={<Radio />} label="Toestel nog in gebruik" />
                    </RadioGroup>
                    <TextField sx={{ margin: 1 }} label="Problem description" color="secondary" variant='outlined' />

                    <h2>School info</h2>
                    <TextField sx={{ margin: 1 }} id="outlined-basic" label="School name" variant="outlined" />

                    <Autocomplete
                      disablePortal
                      id="combo-box-demo"
                      options={schools}
                      sx={{ width: 300 }}
                      renderInput={(params) => <TextField sx={{ margin: 1 }} {...params} label="Repair location" />}
                    />

                    <h2>Contact details</h2>
                    <TextField sx={{ margin: 1 }} label="First name" color="primary" variant='outlined' /><TextField sx={{ margin: 1 }} label="Surname" color="primary" variant='outlined' />
                    <Button sx={{ margin: 1, backgroundColor: "black" }} variant="contained">Submit</Button>
                  </FormControl>

                </Box>
              </Modal>}
            <TabPanel value="1">{incidents.map((incident: Incidents) => <div>
              <CardsIncident data={incident}></CardsIncident>
            </div>
            )}</TabPanel>
            <TabPanel value="2"><h1>Error 404 not found</h1></TabPanel>
            <TabPanel value="3"><h4>Coming soon</h4></TabPanel>
            <TabPanel value="4"><h1>Coming soon</h1></TabPanel>
          </TabContext>
        </Box>
      </Layout>
    )

  }
}

export default Incident;