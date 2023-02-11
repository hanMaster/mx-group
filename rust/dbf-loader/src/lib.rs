mod database;

use crate::database::{doma, kladr, street};
use dbase::{FieldValue, Record, RecordIterator};
use sea_orm::DatabaseConnection;
use sea_orm::{ActiveModelTrait, ActiveValue::Set};
use std::error::Error;
use std::fs::File;
use std::io::BufReader;

pub async fn load(db_name: &str, db: &DatabaseConnection) -> Result<(), Box<dyn Error>> {
    let path = format!("base/{}.DBF", db_name);
    let mut reader = dbase::Reader::from_path(path)?;
    let iter = reader.iter_records();

    if db_name.eq("kladr") {
        save_parsed_kladr(iter, db).await?;
    } else if db_name.eq("doma") {
        save_parsed_doma(iter, db).await?;
    } else if db_name.eq("street") {
        save_parsed_street(iter, db).await?;
    }
    Ok(())
}

async fn save_parsed_kladr(
    iter: RecordIterator<'_, BufReader<File>, Record>,
    db: &DatabaseConnection,
) -> Result<(), Box<dyn Error>> {
    for record_result in iter {
        let record = record_result?;
        let mut rec = kladr::ActiveModel {
            ..Default::default()
        };
        for (name, value) in record {
            let val = match value {
                FieldValue::Character(Some(string)) => string,
                _ => "".to_string(),
            };
            match name {
                x if x.eq("NAME") => rec.name = Set(Some(val)),
                x if x.eq("SOCR") => rec.socr = Set(Some(val)),
                x if x.eq("CODE") => rec.code = Set(Some(val)),
                x if x.eq("INDEX") => rec.index = Set(Some(val)),
                x if x.eq("GNINMB") => rec.gninmb = Set(Some(val)),
                x if x.eq("UNO") => rec.uno = Set(Some(val)),
                x if x.eq("OCATD") => rec.ocatd = Set(Some(val)),
                x if x.eq("STATUS") => rec.status = Set(Some(val)),
                _ => {}
            }
        }
        rec.save(db).await?;
    }
    println!("DB kladr saved");
    Ok(())
}

async fn save_parsed_street(
    iter: RecordIterator<'_, BufReader<File>, Record>,
    db: &DatabaseConnection,
) -> Result<(), Box<dyn Error>> {
    for record_result in iter {
        let record = record_result?;
        let mut rec = street::ActiveModel {
            ..Default::default()
        };
        for (name, value) in record {
            let val = match value {
                FieldValue::Character(Some(string)) => string,
                _ => "".to_string(),
            };
            match name {
                x if x.eq("NAME") => rec.name = Set(Some(val)),
                x if x.eq("SOCR") => rec.socr = Set(Some(val)),
                x if x.eq("CODE") => rec.code = Set(Some(val)),
                x if x.eq("INDEX") => rec.index = Set(Some(val)),
                x if x.eq("GNINMB") => rec.gninmb = Set(Some(val)),
                x if x.eq("UNO") => rec.uno = Set(Some(val)),
                x if x.eq("OCATD") => rec.ocatd = Set(Some(val)),
                _ => {}
            }
        }
        rec.save(db).await?;
    }
    println!("DB street saved");
    Ok(())
}

async fn save_parsed_doma(
    iter: RecordIterator<'_, BufReader<File>, Record>,
    db: &DatabaseConnection,
) -> Result<(), Box<dyn Error>> {
    for record_result in iter {
        let record = record_result?;
        let mut rec = doma::ActiveModel {
            ..Default::default()
        };
        for (name, value) in record {
            let val = match value {
                FieldValue::Character(Some(string)) => string,
                _ => "".to_string(),
            };
            match name {
                x if x.eq("NAME") => rec.name = Set(Some(val)),
                x if x.eq("KORP") => rec.korp = Set(Some(val)),
                x if x.eq("SOCR") => rec.socr = Set(Some(val)),
                x if x.eq("CODE") => rec.code = Set(Some(val)),
                x if x.eq("INDEX") => rec.index = Set(Some(val)),
                x if x.eq("GNINMB") => rec.gninmb = Set(Some(val)),
                x if x.eq("UNO") => rec.uno = Set(Some(val)),
                x if x.eq("OCATD") => rec.ocatd = Set(Some(val)),
                _ => {}
            }
        }
        rec.save(db).await?;
    }
    println!("DB doma saved");
    Ok(())
}
