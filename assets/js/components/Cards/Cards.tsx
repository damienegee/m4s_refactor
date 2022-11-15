import styles from './cards.module.css';
import { Users } from '../../../types';

const Cards  = ({data}:{data:Users}) => {
    return (<>

        <div className={styles.card}>
            <div className={styles.left}>
                    <div className={styles.row}>
                        Firstname : 
                    </div>
                    <div className={styles.row}>
                         Lastname : 
                    </div>
                    <div className={styles.row}>
                            Email : 
                    </div>
                    <div className={styles.row}>
                         Position : 
                    </div>
                    <div className={styles.row}>
                         Location : 
                    </div>
                    </div>
            <div className={styles.right}>
                    <div className={styles.row}>
                        {data.FirstName} 
                    </div>
                    <div className={styles.row}>
                        {data.LastName} 
                    </div>
                    <div className={styles.row}>
                        {data.Email}
                    </div>
                    <div className={styles.row}>
                        {data.Position}
                    </div>
                    <div className={styles.row}>
                        {data.Location}
                    </div>
            </div>
        </div>
    </>)
}
export default Cards;