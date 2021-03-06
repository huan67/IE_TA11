/* External dependencies */
import PropTypes from "prop-types";
import { __ } from "@wordpress/i18n";
import isObject from "lodash/isObject";
import isString from "lodash/isString";
import isUndefined from "lodash/isUndefined";
import ReactHtmlParser from "react-html-parser";

/* Internal dependencies */
import { pickRelevantMediaFiles } from "../../../helpers/pickRelevantMediaFiles";
import { matchIMGsrc } from "../../../helpers/stringHelpers";

/* WordPress dependencies */
const { Component, Fragment } = wp.element;
const { RichText, MediaUpload } = wp.blockEditor;
const { Button } = wp.components;
const { setting_options } = delrcp;

/* Module constants */
const ALLOWED_MEDIA_TYPES = ["image"];

/**
 * A Direction step within a Direction block.
 */
export default class DirectionStep extends Component {
	/**
	 * Constructs a DirectionStep editor component.
	 *
	 * @param {Object} props This component's properties.
	 *
	 * @returns {void}
	 */
	constructor(props) {
		super(props);

		this.onSelectImage = this.onSelectImage.bind(this);
		this.onInsertStep = this.onInsertStep.bind(this);
		this.onRemoveStep = this.onRemoveStep.bind(this);
		this.onMoveStepUp = this.onMoveStepUp.bind(this);
		this.onMoveStepDown = this.onMoveStepDown.bind(this);
		this.setTextRef = this.setTextRef.bind(this);
		this.onFocusText = this.onFocusText.bind(this);
		this.onChangeText = this.onChangeText.bind(this);
		this.onChangeGroupTitle = this.onChangeGroupTitle.bind(this);
	}

	/**
	 * Handles the insert step button action.
	 *
	 * @returns {void}
	 */
	onInsertStep() {
		this.props.insertStep(this.props.index);
	}

	/**
	 * Handles the remove step button action.
	 *
	 * @returns {void}
	 */
	onRemoveStep() {
		this.props.removeStep(this.props.index);
	}

	/**
	 * Handles the move step up button action.
	 *
	 * @returns {void}
	 */
	onMoveStepUp() {
		if (this.props.isFirst) {
			return;
		}
		this.props.onMoveUp(this.props.index);
	}

	/**
	 * Handles the move step down button action.
	 *
	 * @returns {void}
	 */
	onMoveStepDown() {
		if (this.props.isLast) {
			return;
		}
		this.props.onMoveDown(this.props.index);
	}

	/**
	 * Pass the step text editor reference down to the parent component.
	 *
	 * @param {object} ref Reference to the step text editor.
	 *
	 * @returns {void}
	 */
	setTextRef(ref) {
		this.props.editorRef(this.props.index, "text", ref);
	}

	/**
	 * Handles the focus event on the step text editor.
	 *
	 * @returns {void}
	 */
	onFocusText() {
		this.props.onFocus(this.props.index, "text");
	}

	/**
	 * Handles the on change event on the step text editor.
	 *
	 * @param {string} value The new step text.
	 *
	 * @returns {void}
	 */
	onChangeText(value) {
		const {
			onChange,
			index,
			step: { text },
		} = this.props;

		onChange(value, text, index);
	}

	/**
	 * Handles the on change event on the direction group title editor.
	 *
	 * @param {string} value The new direction name.
	 *
	 * @returns {void}
	 */
	onChangeGroupTitle(value) {
		const {
			onChange,
			index,
			step: { text },
		} = this.props;

		onChange(value, text, index, true);
	}

	/**
	 * The insert and remove step buttons.
	 *
	 * @returns {Component} The buttons.
	 */
	getButtons() {
		const {
			step: { id, isGroup },
		} = this.props;

		return (
			<div className="direction-step-button-container">
				{this.getMover()}
				{!isGroup && (
					<MediaUpload
						onSelect={this.onSelectImage}
						allowedTypes={ALLOWED_MEDIA_TYPES}
						value={id}
						render={({ open }) => (
							<Button
								className="direction-step-button direction-step-button-add-image editor-inserter__toggle direction-step-add-media"
								icon="format-image"
								onClick={open}
							/>
						)}
					/>
				)}
				<Button
					className="direction-step-button direction-step-button-delete editor-inserter__toggle"
					icon="trash"
					label={__("Delete step", "delicious-recipes")}
					onClick={this.onRemoveStep}
				/>
				<Button
					className="direction-step-button direction-step-button-add editor-inserter__toggle"
					icon="editor-break"
					label={__("Insert step", "delicious-recipes")}
					onClick={this.onInsertStep}
				/>
			</div>
		);
	}

	/**
	 * The mover buttons.
	 *
	 * @returns {Component} the buttons.
	 */
	getMover() {
		return (
			<Fragment>
				<Button
					className="editor-block-mover__control"
					onClick={this.onMoveStepUp}
					icon="arrow-up-alt2"
					label={__("Move step up", "delicious-recipes")}
					aria-disabled={this.props.isFirst}
				/>
				<Button
					className="editor-block-mover__control"
					onClick={this.onMoveStepDown}
					icon="arrow-down-alt2"
					label={__("Move step down", "delicious-recipes")}
					aria-disabled={this.props.isLast}
				/>
			</Fragment>
		);
	}

	/**
	 * Callback when an image from the media library has been selected.
	 *
	 * @param {Object} media The selected image.
	 *
	 * @returns {void}
	 */
	onSelectImage(media) {
		const {
			onChange,
			index,
			step: { text },
		} = this.props;

		let newText = text.slice();

		const relevantMedia = pickRelevantMediaFiles(media, "step");
		const image = (
			<img
				key={relevantMedia.id}
				alt={relevantMedia.alt}
				title={relevantMedia.title}
				src={relevantMedia.url}
				className={
					setting_options.delrecpe_rcb_settings_print_show_steps_image ===
						"0"
						? "no-print"
						: ""
				}
			/>
		);

		if (newText.push) {
			newText.push(image);
		} else {
			newText = [newText, image];
		}

		onChange(newText, text, index);
	}

	/**
	 * Returns the image src from step contents.
	 *
	 * @param {array} contents The step contents.
	 *
	 * @returns {string|boolean} The image src or false if none is found.
	 */
	static getImageSrc(contents, index = 0) {
		let image = false;
		if (isString(contents)) {
			image = matchIMGsrc(contents);
		}
		if (isObject(contents)) {
			image = contents.filter(
				(node) => node && node.type && node.type === "img"
			);
		}

		if (!image || !image[index]) {
			return false;
		}

		if (!isUndefined(image[index].props)) {
			return image[index].props.src;
		}
		return image[index];
	}

	/**
	 * Renders this component.
	 *
	 * @returns {Component} The direction step editor.
	 */
	render() {
		const {
			isSelected,
			subElement,
			step: { id, text, isGroup },
		} = this.props;

		const isSelectedText = isSelected && subElement === "text";
		const stepClassName = !isGroup
			? "direction-step"
			: "direction-step direction-step-group";

		let textContent = text;
		if (isString(textContent)) {
			// Converting HTML strings into React components
			textContent = ReactHtmlParser(text);
		}

		return (
			<>
				{!isGroup && (
					<li className={stepClassName} key={id}>
						<RichText
							className="direction-step-text"
							tagName="p"
							// unstableOnSetup={this.setTextRef}
							key={`${id}-text`}
							value={textContent}
							onChange={this.onChangeText}
							placeholder={__(
								"Enter instruction description",
								"delicious-recipes"
							)}
							unstableOnFocus={this.onFocusText}
						/>
					</li>
				)}
				{isGroup && (
					<RichText
						className="direction-step-group-title"
						tagName="h4"
						// unstableOnSetup={this.setTextRef}
						key={`${id}-group-title`}
						value={textContent}
						onChange={this.onChangeGroupTitle}
						placeholder={__(
							"Enter section title",
							"delicious-recipes"
						)}
						unstableOnFocus={this.onFocusText}
					/>
				)}
				{isSelectedText && (
					<div className="direction-step-controls-container">
						{this.getButtons()}
					</div>
				)}
			</>
		);
	}
}

DirectionStep.propTypes = {
	index: PropTypes.number.isRequired,
	step: PropTypes.object.isRequired,
	onChange: PropTypes.func.isRequired,
	insertStep: PropTypes.func.isRequired,
	removeStep: PropTypes.func.isRequired,
	onFocus: PropTypes.func.isRequired,
	editorRef: PropTypes.func.isRequired,
	onMoveUp: PropTypes.func.isRequired,
	onMoveDown: PropTypes.func.isRequired,
	subElement: PropTypes.string,
	isSelected: PropTypes.bool.isRequired,
	isFirst: PropTypes.bool.isRequired,
	isLast: PropTypes.bool.isRequired,
};
