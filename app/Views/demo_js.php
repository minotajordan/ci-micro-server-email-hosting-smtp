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
				const [from, setFrom] = useState('');
				const [name, setName] = useState('');
				const [title, setTitle] = useState('');
				const [subject, setSubject] = useState('');
				const [statusSend, setStatusSend] = useState(false);
				const [data_body, setData_body] = useState('');
				const [config_user, setConfig_user] = useState('');
				const [data_token_public, setData_token_public] = useState('');
				const [payload, setPayload] = useState([]);

				useEffect(() => {
					const payJson = {
							config_user: config_user,
							from: from,
							name: name,
							title: title,
							subject: subject,
							data_body: data_body,
							data_token_public: data_token_public
						}
						console.log('payJson', payJson);
						setPayload(payJson);
					}, [
						config_user,config_user,
						from,
						name,
						title,
						subject,
						data_body,
						data_token_public,
					]);

					const send = async () => {
						setStatusSend(false);
						try {
							let response = await fetch('<?= base_url() ?>/api-send', {
								method: 'POST',
								headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
								body: JSON.stringify(payload)
							});
							const data = await response.json();
							// setStatusSend(false);
							if (data.status !== 200) return { error: '@error.response', status: data.status }
							// setData_token_public(data.token_public);
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
							<span className="text-muted">Code example fetch</span>
							</h4>
							<code>
								{'{'}
									<br/>
									config_user: config_user,
									<br/>
									from: from,
									<br/>
									name: name,
									<br/>
									title: title,
									<br/>
									subject: subject,
									<br/>
									data_body: data_body,
									<br/>
									data_token_public: data_token_public
									<br/>
								{'}'}
							</code>
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

								
							</div>

							<hr className="my-4" />
							<h4 className="mb-3">Data email</h4>
							<div className="row needs-validation" noValidate>

								<div className="col-md-6">
								<label htmlFor="cc-name" className="form-label">From</label>
									<input 
										type="text" 
										className="form-control" 
										id="from" 
										placeholder="from (email)" 
										onChange={(e) => setFrom(e.target.value)}
										value={from} 
										required 
									/>
								<small className="text-muted">User email</small>
								<div className="invalid-feedback">
									From email is required
								</div>
								</div>

								<div className="col-md-6">
								<label htmlFor="cc-name" className="form-label">Name</label>
									<input 
										type="text" 
										className="form-control" 
										id="name" 
										placeholder="name (email)" 
										onChange={(e) => setName(e.target.value)}
										value={name} 
										required 
									/>
								<small className="text-muted">User email</small>
								<div className="invalid-feedback">
									Email is required
								</div>
								</div>

								<div className="col-md-6">
								<label htmlFor="cc-name" className="form-label">Title</label>
									<input 
										type="text" 
										className="form-control" 
										id="title" 
										placeholder="title (email)" 
										onChange={(e) => setTitle(e.target.value)}
										value={title} 
										required 
									/>
								<small className="text-muted">User email</small>
								<div className="invalid-feedback">
									Tile email is required
								</div>
								</div>

								<div className="col-md-6">
								<label htmlFor="cc-name" className="form-label">Subject</label>
									<input 
										type="text" 
										className="form-control" 
										id="subject" 
										placeholder="subject (text)" 
										onChange={(e) => setSubject(e.target.value)}
										value={subject} 
										required 
									/>
								<small className="text-muted">User email</small>
								<div className="invalid-feedback">
									Subject is required
								</div>
								</div>
								
								<div className="col-12">
									<label htmlFor="exampleFormControlTextarea1" className="form-label">Body</label>
										<textarea 
											className="form-control" 
											id="data_body" 
											rows={3}
											onChange={(e) => setData_body(e.target.value)}
											value={data_body} 
										/>
								</div>
							<hr className="my-4" />
							
							<button 
								onClick={async () => await send() }
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
										<span >Send Email (DEMO JS)</span>
									}
									
							</button>
							</div>
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