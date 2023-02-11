use dbf_loader::load;
use dotenvy::dotenv;
use sea_orm::Database;
use std::env;
use std::error::Error;
use std::time::Instant;

#[tokio::main]
async fn main() -> Result<(), Box<dyn Error>> {
    dotenv().ok();
    let database_url = env::var("DATABASE_URL")?;
    let db = Database::connect(database_url).await?;

    let start = Instant::now();
    load("kladr", &db).await?;
    load("street", &db).await?;
    load("doma", &db).await?;

    let duration = start.elapsed();
    println!("KLADR load time is: {:?}", duration);

    Ok(())
}
