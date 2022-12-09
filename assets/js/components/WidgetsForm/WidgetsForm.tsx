import { Modal,  FormControlLabel, Checkbox,Button } from "@mui/material"
import {ChangeEvent,useState,MouseEventHandler} from "react"
import styles from "./WidgetsForm.module.css"

interface WidgetsFormProps {
    open: boolean,
    setOpen: (open: boolean) => void,
    activeWidgets:string[],
    setActiveWidgets:(activeWidgets:string[]) => void
}
const WidgetsForm = ({ open, setOpen, activeWidgets, setActiveWidgets}: WidgetsFormProps) => {
    const handleClose = () => setOpen(false);
    const allWidgets: string[] = [
        "doughnut-chart",
        "devices-counter",
        "incidents-counter",
        "users-counter",
        "images-list",
        "mails-list",
        "incidents-list"
    ]
    const [tempWidgets,setTempWidgets] = useState<string[]>(activeWidgets);
    const handleChange = (event:ChangeEvent<HTMLInputElement>,widget:string) => {
        if(event.target.checked){
            setTempWidgets([...tempWidgets,widget])
        }
        else{
            setTempWidgets(tempWidgets.filter(item => item!=widget));
        }
    }
    const handleSave : MouseEventHandler<HTMLButtonElement> = () => {
        setOpen(false);
        setActiveWidgets(tempWidgets);
    }
    return (
        <Modal
            sx={{ display: "flex", justifyContent: "center", alignItems: "center" }}
            open={open}
            onClose={handleClose}
            aria-labelledby="modal-modal-title"
            aria-describedby="modal-modal-description"

        >
            <div className={styles.box}>
                    <h1 className={styles.title}>Edit Widgets</h1>
                    <div className={styles.group}>
                        {allWidgets.map(widget => {
                            const label : string = widget.charAt(0).toUpperCase() + widget.substring(1,widget.indexOf('-'))  +'-'+ widget.charAt(widget.indexOf('-')+1).toUpperCase() + widget.substring(widget.indexOf('-')+2);
                            return(
                                <FormControlLabel className={styles.checkbox} key={widget} control={<Checkbox checked={tempWidgets.includes(widget)} onChange={(event) => {handleChange(event,widget)}} />} label={label} />
                            )
                        })}
                    </div>
                    <Button  variant="contained" onClick={handleSave} className={styles.btn}>Save</Button>
            </div>
        </Modal>
    )
}

export default WidgetsForm