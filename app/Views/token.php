<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
	<meta name="generator" content="Hugo 0.79.0">
	<title> Create Token Micro Server email </title>

	<link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/checkout/">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

	<!-- Bootstrap core CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	<!-- Nota: cuando se despliegue, reemplazar "development.js" con "production.min.js". -->
	<script src="https://unpkg.com/react@17/umd/react.development.js" crossorigin></script>

	<style>
		.bd-placeholder-img {
			font-size: 1.125rem;
			text-anchor: middle;
			-webkit-user-select: none;
			-moz-user-select: none;
			user-select: none;
		}

		@media (min-width: 768px) {
			.bd-placeholder-img-lg {
				font-size: 3.5rem;
			}
		}
	</style>
	<!-- Custom styles for this template -->
	<link href="form-validation.css" rel="stylesheet">
</head>

<body class="bg-light">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
	<!-- Cargamos nuestro componente de React. -->
	<!DOCTYPE html>
	<html>
	<!-- Cargar React. -->
	<!-- Nota: cuando se despliegue, reemplazar "development.js" con "production.min.js". -->
	<script src="https://unpkg.com/react@17/umd/react.development.js" crossorigin></script>
	<script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js" crossorigin></script>
	<script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>

	<body>
		<div id="root"></div>
		<script type="text/babel">
			const { useState, useEffect } = React

			const App = (props) => { 
				const [text, setText] = useState('hello');
				
				const [config_user, setConfig_user] = useState('');
				const [config_password, setConfig_password] = useState('');
				const [config_email_show, setConfig_email_show] = useState('');
				const [config_port, setConfig_port] = useState(587);
				const [config_host, setConfig_host] = useState('smtp.gmail.com');
				const [data_token, setData_token] = useState('');
				const [config_site, setConfig_site] = useState('');
				const [statusSend, setStatusSend] = useState(false);

				const [data_token_private, setData_token_private] = useState('');
				const [data_token_public, setData_token_public] = useState('');
				
				
				const [payload, setPayload] = useState([]);

				useEffect(() => {
					const payJson = {
						config_user: config_user,
						config_password: config_password,
						config_email_show: config_email_show,
						config_port: config_port,
						config_host: config_host,
						data_token: data_token,
					}
					console.log('payJson', payJson);
					setPayload(payJson);
				}, [
						config_user, 
						config_password, 
						config_port, 
						config_host,
						config_email_show,
						config_site
					]);

					const send = async () => {
						setStatusSend(true);
						try {
							let response = await fetch('<?= base_url() ?>/generate-token', {
								method: 'POST',
								headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
								body: JSON.stringify(payload)
							});
							const data = await response.json();
							setStatusSend(false);
							if (data.status !== 200) return { error: '@error.response', status: data.status }
							setData_token_private(data.token_private);
							setData_token_public(data.token_public);
        					return data;
						} catch (e) {
						}
					}

				return (
				<div className='container'>
					<div className="container">
					<main>
						<div className="py-5 text-center">
						<h2>Create Token Micro Server Email</h2>
						<p className="lead">Data send email .</p>
						</div>
						<div className="row g-3">
						<div className="col-md-5 col-lg-4 order-md-last">
							<h4 className="d-flex justify-content-between align-items-center mb-3">
							<span className="text-muted">Recent email</span>
							<span className="badge bg-secondary rounded-pill">3</span>
							</h4>
							<ul className="list-group mb-3">
							<li className="list-group-item d-flex justify-content-between lh-sm">
								<div>
								<span className="text-muted">10:56 PM</span>
								<h6 className="my-0">Email user config: user@asd.com </h6>
								<small className="text-muted">Email user send: send@asd.com </small>
								<br />
								<small className="text-muted"><b>Title: </b>Brief description</small>
								<br />
								<small className="text-muted"><b>Sub title: </b>Brief description</small>
								</div>
								<span className="text-muted">20/02/2021</span>
							</li>
							</ul>
							<form className="card p-2">
							<div className="input-group">
								<input type="text" className="form-control" placeholder="Promo code" />
								<button type="submit" className="btn btn-secondary">Redeem</button>
							</div>
							</form>
						</div>
						<div className="col-md-7 col-lg-8">

							<h4 className="mb-3">Aut</h4>
							<div className="row gy-3">
							
								<div className="col-md-6">
								<label htmlFor="cc-name" className="form-label">User</label>
									<input 
										type="text" 
										className="form-control" 
										id="config_user" 
										placeholder="config_user (email)" 
										onChange={(e) => setConfig_user(e.target.value)}
										value={config_user} 
										required 
									/>
								<small className="text-muted">User email</small>
								<div className="invalid-feedback">
									Email is required
								</div>
								</div>
								

								<div className="col-md-6">
								<label htmlFor="cc-number" className="form-label">Password</label>
								<input 
										type="text" 
										className="form-control" 
										id="config_password" 
										placeholder="config_password (password)"  
										onChange={(e) => setConfig_password(e.target.value)}
										value={config_password} 
										required />
								<div className="invalid-feedback">
									Password email is require
								</div>
								</div>
								
								<div className="col-md-6">
								<label htmlFor="cc-name" className="form-label">Site</label>
									<input 
										type="text" 
										className="form-control" 
										id="config_site" 
										placeholder="config_site (url)" 
										onChange={(e) => setConfig_site(e.target.value)}
										value={config_site} 
										required 
									/>
								<small className="text-muted">User email</small>
								<div className="invalid-feedback">
									Site is required
								</div>
								</div>
								
								<div className="col-md-3">
								<label htmlFor="cc-expiration" className="form-label">HOST</label>
								<input 
										type="text" 
										className="form-control" 
										id="config_host" 
										placeholder="config_host (text)"  
										onChange={(e) => setConfig_host(e.target.value)}
										value={config_host} 
										required 
								/>
								<div className="invalid-feedback">
									Expiration date required
								</div>
								</div>
								<div className="col-md-3">
								<label htmlFor="cc-cvv" className="form-label">PORT</label>
								<input 
										type="text" 
										className="form-control" 
										id="config_port"
										placeholder="config_port (number)"  
										onChange={(e) => setConfig_port(e.target.value)}
										value={config_port}
										required 
									/>
								<div className="invalid-feedback">
									Security port is required
								</div>
								</div>
							</div>

							<hr className="my-4" />
							<h4 className="mb-3">Token</h4>
							<form className="needs-validation" noValidate>
								<div className="col-12">
									<label htmlFor="exampleFormControlTextarea1" className="form-label">Public token</label>
										<textarea 
											className="form-control" 
											id="data_token_public" 
											rows={5}
											onChange={(e) => setData_token_public(e.target.value)}
											value={data_token_public} 
										/>
								</div>

								<div className="col-12">
									<label htmlFor="exampleFormControlTextarea1" className="form-label">Private token</label>
										<textarea 
											className="form-control" 
											id="data_token_private" 
											rows={3}
											onChange={(e) => setData_token_private(e.target.value)}
											value={data_token_private} 
										/>
								</div>
							<hr className="my-4" />
							
							<button 
								onClick={async () => { await send() } }
								className="w-100 btn btn-warning btn-lg" 
								type="submit"
								disabled={statusSend}
								>
									{
										statusSend ?
										<div className="spinner-border" role="status">
											<span className="sr-only"></span>
										</div>
										:
										<span >Generar</span>
									}
									
							</button>
							</form>
						</div>
						</div>
					</main>
					<footer className="my-5 pt-5 text-muted text-center text-small">
						<p className="mb-1">© 2017–2021 Company Name</p>
						<ul className="list-inline">
						<li className="list-inline-item"><a href="#">Privacy</a></li>
						<li className="list-inline-item"><a href="#">Terms</a></li>
						<li className="list-inline-item"><a href="#">Support</a></li>
						</ul>
					</footer>
					</div>
				</div>
				);
			}

			const rootElement = document.getElementById('root')
			ReactDOM.render(<App />, rootElement)
		</script>
	</body>

	</html>